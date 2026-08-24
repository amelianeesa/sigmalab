<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPengadaan;
use App\Models\Barang;
use App\Models\TransaksiBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;
use App\Enums\PeranPengguna;
use Carbon\Carbon;

class PengadaanController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index()
    {
        if (!$this->permissionService->userHasAccess(Auth::user(), 'pengadaan', 'lihat') && !$this->permissionService->userHasAccess(Auth::user(), 'pengadaan', 'tambah_ubah') && !$this->permissionService->userHasAccess(Auth::user(), 'pengadaan', 'full')) {
            abort(403, 'Anda tidak memiliki akses ke modul pengadaan ini.');
        }

        $pengadaans = PermintaanPengadaan::with(['barang', 'pemohon', 'penyetuju'])->orderBy('created_at', 'desc')->get();
        $barangList = Barang::all();

        return view('pengadaan.index', compact('pengadaans', 'barangList'));
    }

    public function store(Request $request)
    {
        $roleName = Auth::user()->role->nama_role ?? '';
        $allowedToRequest = [
            'Analis',
            'Koordinator Laboratorium',
            'Admin Lab',
            'Admin Aplikasi'
        ];

        if (!in_array($roleName, $allowedToRequest)) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengajukan pengadaan');
        }

        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,barang_id',
            'jumlah_diminta' => 'required|numeric|min:0.1',
            'alasan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
           
            $file->move(public_path('uploads/pengadaan'), $filename);
            $pathFoto = 'uploads/pengadaan/' . $filename;
        }
        PermintaanPengadaan::create([
            'barang_id' => $validated['barang_id'],
            'jumlah_diminta' => $validated['jumlah_diminta'],
            'alasan' => $validated['alasan'],
            'foto' => $pathFoto,
            'status' => 'diajukan',
            'diajukan_oleh' => Auth::id(),
            'tanggal_pengajuan' => now()->toDateString(),
        ]);

        return redirect()->route('pengadaan.index')->with('success', 'Permintaan pengadaan berhasil diajukan dan menunggu persetujuan HR & GA');
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->bulan ?: date('m');
        $tahun = $request->tahun ?: date('Y');

        $pengadaans = PermintaanPengadaan::with(['barang', 'pemohon', 'penyetuju'])
            ->whereMonth('tanggal_pengajuan', $bulan)
            ->whereYear('tanggal_pengajuan', $tahun)
            ->orderBy('tanggal_pengajuan', 'asc')
            ->get();
        $hrgaName = Auth::user()?->personil?->nama ?? Auth::user()?->username ?? 'HR & GA Officer';
        $kabidUser = \App\Models\User::whereHas('role', function($q) {
            $q->where('nama_role', \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value);
        })->first();
        $kabidName = $kabidUser ? ($kabidUser->personil?->nama ?? $kabidUser->username) : '................................';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pengadaan.pdf', compact('pengadaans', 'bulan', 'tahun', 'hrgaName', 'kabidName'));
        
        return $pdf->download("Laporan_Pengadaan_{$tahun}_{$bulan}.pdf");
    }

    public function approve(Request $request, $id)
    {
        $pengadaan = PermintaanPengadaan::findOrFail($id);
        
        $roleName = Auth::user()->role->nama_role ?? '';
        
        if (!in_array($roleName, [PeranPengguna::HR_GA_OFFICER->value, PeranPengguna::ADMIN_APLIKASI->value])) {
            return back()->with('error', 'Hanya HR & GA yang dapat memproses pengadaan.');
        }

        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak,diproses,selesai',
            'catatan_approval' => 'nullable|string'
        ]);

        DB::transaction(function () use ($validated, $pengadaan) {
            $pengadaan->status = $validated['status'];
            $pengadaan->disetujui_oleh = Auth::id();
            $pengadaan->tanggal_keputusan = now()->toDateString();
            $pengadaan->catatan_approval = $validated['catatan_approval'] ?? null;
            $pengadaan->save();

            if ($validated['status'] === 'selesai') {
                $barang = Barang::where('barang_id', $pengadaan->barang_id)->lockForUpdate()->first();
                if ($barang) {
                    $barang->penerimaan += $pengadaan->jumlah_diminta;
                    $barang->saldo_akhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;
                    $barang->save();

                    TransaksiBarang::create([
                        'barang_id' => $barang->barang_id,
                        'jumlah_penerimaan' => $pengadaan->jumlah_diminta,
                        'harga' => $barang->harga_rata ?? 0,
                    ]);
                }
            }
        });

        return redirect()->route('pengadaan.index')->with('success', 'Status pengadaan berhasil diupdate.');
    }

    public function konfirmasiTerima(Request $request, $id)
    {
        $validated = $request->validate([
            'foto_diterima' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'nama_penerima' => 'required|string|max:100',
            'tgl_exp' => 'required|date',
        ]);

        $pengadaan = PermintaanPengadaan::findOrFail($id);
        if (!in_array($pengadaan->status, ['disetujui', 'diproses'])) {
            return back()->with('error', 'Pengadaan harus disetujui atau diproses terlebih dahulu sebelum dikonfirmasi.');
        }

        DB::transaction(function () use ($request, $pengadaan) {
            $file = $request->file('foto_diterima');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pengadaan'), $filename);
            $pathFoto = 'uploads/pengadaan/' . $filename;

            $pengadaan->foto_diterima = $pathFoto;
            $pengadaan->nama_penerima = $request->nama_penerima;
            $pengadaan->waktu_diterima = Carbon::now('Asia/Jakarta');
            $pengadaan->status = 'selesai';
            $pengadaan->save();

            $barang = Barang::where('barang_id', $pengadaan->barang_id)->lockForUpdate()->first();
            if ($barang) {
                $barang->penerimaan += $pengadaan->jumlah_diminta;
                $barang->saldo_akhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;
                
                // $barang->tgl_exp = $request->tgl_exp; // Update tanggal expired sesuai fisik baru
                // Logika cerdas: Jika tgl_exp barang yang baru lebih awal dari tgl_exp lama (atau tgl_exp lama kosong), 
                // maka perbarui tgl_exp utama agar mencerminkan barang yang paling cepat expired (FEFO).
                if (empty($barang->tgl_exp) || $request->tgl_exp < $barang->tgl_exp) {
                    $barang->tgl_exp = $request->tgl_exp;
                }
                $barang->save();    
                TransaksiBarang::create([
                    'barang_id' => $barang->barang_id,
                    'jumlah_penerimaan' => $pengadaan->jumlah_diminta,
                    'harga' => $barang->harga_rata ?? 0,
                    'tgl_exp' => $request->tgl_exp,
                ]);
            }
        });

        return back()->with('success', 'Konfirmasi penerimaan berhasil!');
    }    

    public function destroy($id)
    {
        $pengadaan = PermintaanPengadaan::findOrFail($id);
        
        if ($pengadaan->status !== 'diajukan') {
            return back()->with('error', 'Hanya permintaan yang berstatus diajukan yang bisa dihapus');
        }

        if ($pengadaan->foto && file_exists(public_path($pengadaan->foto))) {
            @unlink(public_path($pengadaan->foto));
        }

        $pengadaan->delete();
        return redirect()->route('pengadaan.index')->with('success', 'Permintaan berhasil dibatalkan.');
    }
}
