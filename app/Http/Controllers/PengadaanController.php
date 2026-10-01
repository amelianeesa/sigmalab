<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPengadaan;
use App\Models\Barang;
use App\Models\TransaksiBarang;
use App\Models\User;
use App\Services\PermissionService;
use App\Enums\PeranPengguna;
use App\Http\Requests\StorePengadaanRequest;
use App\Http\Requests\ApprovePengadaanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PengadaanController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index()
    {
        if (
            !$this->permissionService->userHasAccess(Auth::user(), 'pengadaan', 'lihat') &&
            !$this->permissionService->userHasAccess(Auth::user(), 'pengadaan', 'tambah_ubah') &&
            !$this->permissionService->userHasAccess(Auth::user(), 'pengadaan', 'full')
        ) {
            abort(403, 'Anda tidak memiliki akses ke modul pengadaan ini.');
        }

        $pengadaans = PermintaanPengadaan::with(['barang', 'pemohon', 'penyetuju'])
            ->orderBy('created_at', 'desc')
            ->get();

        $barangList = Barang::all();
        $currentUserRole = strtolower(Auth::user()->role->nama_role ?? '');

        return view('pengadaan.index', compact('pengadaans', 'barangList', 'currentUserRole'));
    }

    public function store(StorePengadaanRequest $request)
    {
        $roleName = strtolower(Auth::user()->role->nama_role ?? '');

        $allowedToRequest = [
            strtolower(PeranPengguna::ANALIS->value),
            strtolower(PeranPengguna::KOORDINATOR_LAB->value),
            strtolower(PeranPengguna::GA_OFFICER->value),
            strtolower(PeranPengguna::ADMIN_APLIKASI->value),
        ];

        if (!in_array($roleName, $allowedToRequest) && !str_contains($roleName, 'analis') && !str_contains($roleName, 'koor')) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengajukan pengadaan.');
        }

        $validated = $request->validated();

        $tahun = (int) $request->input('target_tahun', 0);
        $bulan = (int) $request->input('target_bulan', 0);
        $hari  = (int) $request->input('target_hari', 0);

        $totalHari = ($tahun * 365) + ($bulan * 30) + $hari;

        if ($totalHari <= 0) {
            return back()->withErrors(['target_hari' => 'Target batas waktu pengadaan harus diisi minimal 1 hari.'])->withInput();
        }

        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pengadaan'), $filename);
            $pathFoto = 'uploads/pengadaan/' . $filename;
        }

        if (str_contains($roleName, 'analis')) {
            $statusAwal = 'menunggu_koordinator';
            $pesanSukses = 'Pengajuan berhasil dibuat dan menunggu persetujuan Koordinator Lab.';
        } else {
            $statusAwal = 'menunggu_ga';
            $pesanSukses = 'Pengajuan berhasil diajukan dan langsung diteruskan ke GA.';
        }

        $pengadaan = PermintaanPengadaan::create([
            'barang_id' => $validated['barang_id'],
            'jumlah_diminta' => $validated['jumlah_diminta'],
            'target_hari' => $totalHari,
            'alasan' => $validated['alasan'] ?? null,
            'foto' => $pathFoto,
            'status' => $statusAwal,
            'diajukan_oleh' => Auth::id(),
            'tanggal_pengajuan' => Carbon::now()->toDateString(),
        ]);

        if ($statusAwal === 'menunggu_koordinator') {
            $this->notifikasiKeKoordinator($pengadaan, 'Pengajuan baru memerlukan persetujuan Anda.');
        } else {
            $this->kirimEmailNotifikasiKeGAAndCC($pengadaan);
            $this->notifikasiInAppGAAndKabid($pengadaan, 'Pengajuan pengadaan baru membutuhkan persetujuan GA.');
        }

        return redirect()->route('pengadaan.index')->with('success', $pesanSukses);
    }

    public function approve(ApprovePengadaanRequest $request, $id)
    {
        $pengadaan = PermintaanPengadaan::findOrFail($id);
        $roleName = strtolower(Auth::user()->role->nama_role ?? '');
        $validated = $request->validated();
        $statusBaru = $validated['status'];

        $isKoor = str_contains($roleName, 'koor');
        $isGaOrAdmin = str_contains($roleName, 'ga') || str_contains($roleName, 'admin');

        if ($isKoor && !in_array($pengadaan->status, ['diajukan', 'menunggu_koordinator'])) {
            return redirect()->back()->with('error', 'Pengadaan ini sudah melewati tahap verifikasi Koordinator.');
        }

        if ($isGaOrAdmin && !in_array($pengadaan->status, ['menunggu_ga', 'disetujui', 'diproses_po', 'pembelian'])) {
            return redirect()->back()->with('error', 'Pengadaan ini belum sampai di tahap GA.');
        }

        DB::transaction(function () use ($request, $validated, $pengadaan, $roleName, $statusBaru, $isKoor, $isGaOrAdmin) {

            $labelPeranPenolak = $isKoor ? 'Ditolak oleh: Koordinator Lab' : 'Ditolak oleh: GA Officer';
            $catatanAsli = $validated['catatan_approval'] ?? 'Tidak ada alasan spesifik.';
            $catatanLengkapPenolakan = "{$labelPeranPenolak}. Alasan: {$catatanAsli}";

            if ($statusBaru === 'ditolak') {
                $pengadaan->status = 'ditolak';
                $pengadaan->disetujui_oleh = Auth::id();
                $pengadaan->tanggal_keputusan = Carbon::now()->toDateString();
                $pengadaan->catatan_approval = $catatanLengkapPenolakan;
                $pengadaan->save();

                $this->notifikasiPenolakanKeKoordinator($pengadaan);
                return;
            }

            if ($isKoor) {
                $pengadaan->status = 'menunggu_ga';
                $pengadaan->save();

                $this->kirimEmailNotifikasiKeGAAndCC($pengadaan);
                $this->notifikasiInAppGAAndKabid($pengadaan, 'Pengajuan telah disetujui Koordinator dan menunggu persetujuan GA.');

            } 
            elseif ($isGaOrAdmin) {
                $pengadaan->disetujui_oleh = Auth::id();
                $pengadaan->tanggal_keputusan = Carbon::now()->toDateString();
                
                if ($statusBaru === 'diproses') {
                    $metode = $request->input('metode_proses', 'PO'); 
                    
                    if ($metode === 'Pembelian') {
                        $pengadaan->status = 'pembelian';
                    } else {
                        $pengadaan->status = 'diproses_po';
                    }
                    
                    $pengadaan->catatan_po = $request->input('catatan_po');
                } else {
                    $pengadaan->status = $statusBaru;
                }
            
                if (isset($validated['catatan_approval'])) {
                    $pengadaan->catatan_approval = $validated['catatan_approval'];
                }
            
                $pengadaan->save();
            }
             else {
                throw new \Exception('Anda tidak memiliki izin untuk memproses persetujuan ini.');
            }
        });

        return redirect()->route('pengadaan.index')->with('success', 'Status pengadaan berhasil diperbarui.');
    }

    public function konfirmasiTerima(Request $request, $id)
    {
        $request->validate([
            'foto_diterima' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'nama_penerima' => 'required|string|max:100',
            'tgl_exp' => 'nullable|date',
        ]);

        $pengadaan = PermintaanPengadaan::findOrFail($id);

        if (!in_array($pengadaan->status, ['disetujui', 'diproses'])) {
            return back()->with('error', 'Pengadaan harus disetujui atau diproses terlebih dahulu sebelum dikonfirmasi.');
        }

        DB::transaction(function () use ($request, $pengadaan) {
            $pathFoto = null;
            if ($request->hasFile('foto_diterima')) {
                $file = $request->file('foto_diterima');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/pengadaan'), $filename);
                $pathFoto = 'uploads/pengadaan/' . $filename;
            }

            $pengadaan->foto_diterima = $pathFoto;
            $pengadaan->nama_penerima = $request->nama_penerima;
            $pengadaan->waktu_diterima = Carbon::now('Asia/Jakarta');
            $pengadaan->status = 'selesai';
            $pengadaan->save();

            $barang = Barang::where('barang_id', $pengadaan->barang_id)->lockForUpdate()->first();
            if ($barang) {
                $barang->penerimaan += $pengadaan->jumlah_diminta;
                $barang->saldo_akhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;

                if ($request->filled('tgl_exp')) {
                    if (empty($barang->tgl_exp) || $request->tgl_exp < $barang->tgl_exp) {
                        $barang->tgl_exp = $request->tgl_exp;
                    }
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

        $kabidUser = User::whereHas('role', function ($q) {
            $q->where('nama_role', PeranPengguna::KABID_DUKUNGAN_BISNIS->value);
        })->first();

        $kabidName = $kabidUser ? ($kabidUser->personil?->nama ?? $kabidUser->username) : '................................';

        $pdf = Pdf::loadView('pengadaan.pdf', compact('pengadaans', 'bulan', 'tahun', 'hrgaName', 'kabidName'));

        return $pdf->download("Laporan_Pengadaan_{$tahun}_{$bulan}.pdf");
    }

    public function destroy($id)
    {
        $pengadaan = PermintaanPengadaan::findOrFail($id);
        $roleName = Auth::user()->role->nama_role ?? '';
        $isAdminAplikasi = $roleName === PeranPengguna::ADMIN_APLIKASI->value;

        if (!$isAdminAplikasi && !in_array($pengadaan->status, ['diajukan', 'menunggu_koordinator'])) {
            return back()->with('error', 'Hanya permintaan yang belum diproses yang bisa dibatalkan.');
        }

        if (!$isAdminAplikasi && $pengadaan->diajukan_oleh !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk membatalkan pengajuan ini.');
        }

        if ($pengadaan->foto && file_exists(public_path($pengadaan->foto))) {
            @unlink(public_path($pengadaan->foto));
        }

        if ($pengadaan->foto_diterima && file_exists(public_path($pengadaan->foto_diterima))) {
            @unlink(public_path($pengadaan->foto_diterima));
        }

        $pengadaan->delete();

        $pesan = $isAdminAplikasi ? 'Data pengadaan berhasil dihapus permanen.' : 'Permintaan berhasil dibatalkan.';

        return redirect()->route('pengadaan.index')->with('success', $pesan);
    }

    private function urlPengadaan($pengadaan)
    {
        return route('pengadaan.index', [], false) . '#pengadaan-' . $pengadaan->permintaan_id;
    }

    private function kirimEmailNotifikasiKeGAAndCC($pengadaan)
    {
        $emailGA = User::whereHas('role', function ($q) {
            $q->where('nama_role', PeranPengguna::GA_OFFICER->value);
        })->pluck('email')->filter()->toArray();

        $emailKabid = User::whereHas('role', function ($q) {
            $q->whereIn('nama_role', [
                PeranPengguna::KABID_INSPEKSI->value,
                PeranPengguna::KABID_DUKUNGAN_BISNIS->value,
            ]);
        })->pluck('email')->filter()->toArray();

        if (!empty($emailGA)) {
            $mail = Mail::to($emailGA);
            if (!empty($emailKabid)) {
                $mail->cc($emailKabid);
            }

            if (class_exists('\App\Mail\PengajuanPengadaanMail')) {
                $mail->send(new \App\Mail\PengajuanPengadaanMail($pengadaan));
            }
        }
    }

    private function notifikasiInAppGAAndKabid($pengadaan, $pesan)
    {
        $users = User::whereHas('role', function ($q) {
            $q->whereIn('nama_role', [
                PeranPengguna::GA_OFFICER->value,
                PeranPengguna::KABID_INSPEKSI->value,
                PeranPengguna::KABID_DUKUNGAN_BISNIS->value,
            ]);
        })->get();

        $url = $this->urlPengadaan($pengadaan);

        foreach ($users as $user) {
            DB::table('notifikasi')->insert([
                'users_id' => $user->users_id,
                'jenis_notifikasi' => 'stok',
                'pesan' => "{$pesan} (Barang: {$pengadaan->barang->nama_barang})",
                'url' => $url,
                'is_read' => 0,
                'created_at' => now(),
            ]);
        }
    }

    private function notifikasiKeKoordinator($pengadaan, $pesan)
    {
        $koordinators = User::whereHas('role', function ($q) {
            $q->where('nama_role', PeranPengguna::KOORDINATOR_LAB->value);
        })->get();

        $url = $this->urlPengadaan($pengadaan);

        foreach ($koordinators as $koor) {
            DB::table('notifikasi')->insert([
                'users_id' => $koor->users_id,
                'jenis_notifikasi' => 'stok',
                'pesan' => "{$pesan} (Barang: {$pengadaan->barang->nama_barang})",
                'url' => $url,
                'is_read' => 0,
                'created_at' => now(),
            ]);
        }
    }

    private function notifikasiPenolakanKeKoordinator($pengadaan)
    {
        $koordinators = User::whereHas('role', function ($q) {
            $q->where('nama_role', PeranPengguna::KOORDINATOR_LAB->value);
        })->get();

        $pesanPenolakan = "Pengajuan pengadaan barang \"{$pengadaan->barang->nama_barang}\" DITOLAK. Detail: {$pengadaan->catatan_approval}";
        $url = $this->urlPengadaan($pengadaan);

        foreach ($koordinators as $koor) {
            DB::table('notifikasi')->insert([
                'users_id' => $koor->users_id,
                'jenis_notifikasi' => 'stok',
                'pesan' => $pesanPenolakan,
                'url' => $url,
                'is_read' => 0,
                'created_at' => now(),
            ]);

            if ($koor->email && class_exists('\App\Mail\PenolakanPengadaanMail')) {
                Mail::to($koor->email)->send(new \App\Mail\PenolakanPengadaanMail($pengadaan, $pesanPenolakan));
            }
        }
    }

    private function formatTargetWaktu($totalHari)
    {
        if (!$totalHari || $totalHari <= 0) {
            return '-';
        }

        $tahun = floor($totalHari / 365);
        $sisaHariSetelahTahun = $totalHari % 365;

        $bulan = floor($sisaHariSetelahTahun / 30);
        $hari  = $sisaHariSetelahTahun % 30;

        $hasil = [];
        if ($tahun > 0) {
            $hasil[] = "{$tahun} thn";
        }
        if ($bulan > 0) {
            $hasil[] = "{$bulan} bln";
        }
        if ($hari > 0 || empty($hasil)) {
            $hasil[] = "{$hari} hari";
        }

        return implode(' ', $hasil);
    }

    public function prosesPo(Request $request, $id)
    {
        $request->validate([
            'catatan_po' => 'nullable|string|max:255'
        ]);

        $pengadaan = PermintaanPengadaan::findOrFail($id);
        $pengadaan->status = 'diproses';
        $pengadaan->catatan_po = $request->catatan_po;
        $pengadaan->save();

        return redirect()->back()->with('success', 'Pengadaan berhasil diproses ke tahap PO');
    }

    public function updateProgres(Request $request, $id)
    {
        $request->validate([
            'catatan_po' => 'required|string',
        ]);
    
        $pengadaan = PermintaanPengadaan::findOrFail($id);
        
        $pengadaan->catatan_po = $request->catatan_po;
        $pengadaan->save();
    
        return redirect()->back()->with('success', 'Catatan progres berhasil diperbarui!');
    }

    public function batalProgres(Request $request, $id)
    {
        $request->validate([
            'alasan_batal' => 'required|string|max:255',
        ]);
    
        $pengadaan = PermintaanPengadaan::findOrFail($id);
        
        $pengadaan->status = 'ditolak';
        $pengadaan->catatan_approval = 'Dibatalkan oleh: GA. Alasan: ' . $request->alasan_batal;
        $pengadaan->save();
    
        return redirect()->back()->with('success', 'Proses pengadaan berhasil dibatalkan.');
    }
}