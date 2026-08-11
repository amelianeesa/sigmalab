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
            PeranPengguna::ANALIS->value, 
            PeranPengguna::KOORDINATOR_LAB->value, 
            PeranPengguna::ADMIN_LAB->value, 
            PeranPengguna::ADMIN_APLIKASI->value, 
            PeranPengguna::KABID_DUKUNGAN_BISNIS->value
        ];

        if (!in_array($roleName, $allowedToRequest)) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengajukan pengadaan.');
        }

        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,barang_id',
            'jumlah_diminta' => 'required|numeric|min:0.1',
            'alasan' => 'nullable|string'
        ]);

        PermintaanPengadaan::create([
            'barang_id' => $validated['barang_id'],
            'jumlah_diminta' => $validated['jumlah_diminta'],
            'alasan' => $validated['alasan'],
            'status' => 'diajukan',
            'diajukan_oleh' => Auth::id(),
            'tanggal_pengajuan' => now()->toDateString(),
        ]);

        return redirect()->route('pengadaan.index')->with('success', 'Permintaan pengadaan berhasil diajukan dan menunggu persetujuan Kabid.');
    }

    public function approve(Request $request, $id)
    {
        $pengadaan = PermintaanPengadaan::findOrFail($id);
        
        $roleName = Auth::user()->role->nama_role ?? '';
        
        if (!in_array($roleName, [PeranPengguna::KABID_DUKUNGAN_BISNIS->value, PeranPengguna::ADMIN_APLIKASI->value])) {
            return back()->with('error', 'Hanya Kabid Dukungan Bisnis yang dapat memproses pengadaan.');
        }

        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak,diproses,selesai',
            'catatan_approval' => 'nullable|string'
        ]);

        DB::transaction(function () use ($validated, $pengadaan) {
            $pengadaan->status = $validated['status'];
            $pengadaan->disetujui_oleh = Auth::id();
            $pengadaan->tanggal_keputusan = now()->toDateString();
            $pengadaan->catatan_approval = $validated['catatan_approval'];
            $pengadaan->save();

            if ($validated['status'] === 'selesai') {
                $barang = $pengadaan->barang;
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

    public function destroy($id)
    {
        $pengadaan = PermintaanPengadaan::findOrFail($id);
        
        if ($pengadaan->status !== 'diajukan') {
            return back()->with('error', 'Hanya permintaan yang berstatus diajukan yang bisa dihapus.');
        }

        $pengadaan->delete();
        return redirect()->route('pengadaan.index')->with('success', 'Permintaan berhasil dibatalkan.');
    }
}
