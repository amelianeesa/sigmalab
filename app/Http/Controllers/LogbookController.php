<?php

namespace App\Http\Controllers;

use App\Enums\PeranPengguna;
use App\Models\Alat;
use App\Models\LogbookPeminjaman;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Peminjaman alat berbentuk logbook per alat (pengganti buku catatan manual):
 * Tanggal, Pekerjaan, Petugas, Paraf. Koordinator Lab menyetujui tiap catatan.
 */
class LogbookController extends Controller
{
    private function pengelola(): bool
    {
        return in_array(Auth::user()->role?->nama_role, PeranPengguna::pengelolaPeminjaman(), true);
    }

    private function bisaMengisi(): bool
    {
        // Koordinator (penyetuju), HR, dan GA hanya memantau.
        $role = Auth::user()->role?->nama_role;
        $tidakMengisi = array_merge(PeranPengguna::pengelolaPeminjaman(), [PeranPengguna::HR_OFFICER->value, PeranPengguna::GA_OFFICER->value]);
        if (in_array($role, $tidakMengisi, true)) {
            return false;
        }

        return Auth::user()->hasModulAccess('peminjaman', 'tambah_ubah');
    }

    private function namaUser(): string
    {
        return Auth::user()->personil->nama ?? Auth::user()->username;
    }

    private function alatBisaDipinjam()
    {
        return Alat::where('dapat_dipinjam', true);
    }

    // ------------------------------------------------------------ halaman

    public function index(Request $request)
    {
        $cari = trim((string) $request->input('search', ''));

        $alatList = $this->alatBisaDipinjam()
            ->when($cari !== '', function ($q) use ($cari) {
                $like = '%' . addcslashes($cari, '%_\\') . '%';
                $q->where(fn($w) => $w->where('nama_alat', 'like', $like)->orWhere('kode_alat', 'like', $like));
            })
            ->orderBy('nama_alat')->get();

        $ringkas = LogbookPeminjaman::whereIn('alat_id', $alatList->pluck('alat_id'))
            ->where('status', '!=', 'ditolak')
            ->selectRaw("alat_id, count(*) as total, sum(status = 'diajukan') as menunggu")
            ->groupBy('alat_id')->get()->keyBy('alat_id');

        // Status terkini per alat. Kolom paraf (gambar) sengaja tidak diambil agar ringan.
        $kolom = ['logbook_peminjaman_id', 'alat_id', 'tanggal', 'rencana_kembali', 'pekerjaan', 'petugas', 'status'];
        $idAlat = $alatList->pluck('alat_id');

        // Sedang dipinjam = catatan berstatus "disetujui" (belum dicatat dikembalikan).
        $sedangDipinjam = LogbookPeminjaman::select($kolom)->whereIn('alat_id', $idAlat)
            ->where('status', 'disetujui')
            ->orderByDesc('tanggal')->orderByDesc('logbook_peminjaman_id')
            ->get()->unique('alat_id')->keyBy('alat_id');

        // Peminjaman terakhir (selain yang ditolak).
        $terakhir = LogbookPeminjaman::select($kolom)->whereIn('alat_id', $idAlat)
            ->where('status', '!=', 'ditolak')
            ->orderByDesc('tanggal')->orderByDesc('logbook_peminjaman_id')
            ->get()->unique('alat_id')->keyBy('alat_id');

        return view('peminjaman.index', compact('alatList', 'ringkas', 'cari', 'sedangDipinjam', 'terakhir'));
    }

    public function show($id)
    {
        $alat = $this->alatBisaDipinjam()->findOrFail($id);

        $baris = LogbookPeminjaman::with(['pembuat.personil', 'penyetuju.personil'])
            ->where('alat_id', $alat->alat_id)
            ->orderBy('tanggal')->orderBy('logbook_peminjaman_id')
            ->get();

        return view('peminjaman.logbook', [
            'alat' => $alat,
            'baris' => $baris,
            'bisaMengisi' => $this->bisaMengisi(),
            'pengelola' => $this->pengelola(),
            'namaDefault' => $this->namaUser(),
        ]);
    }

    // ------------------------------------------------------------ isi & approval

    public function store(Request $request, $id)
    {
        abort_unless($this->bisaMengisi(), 403);
        $alat = $this->alatBisaDipinjam()->findOrFail($id);

        if ($alat->kondisi_barang !== 'baik') {
            throw ValidationException::withMessages(['pekerjaan' => 'Alat ini sedang rusak/perbaikan dan tidak dapat dipinjam.']);
        }

        $data = $request->validate([
            'tanggal' => 'required|date',
            'rencana_kembali' => 'nullable|date|after_or_equal:tanggal',
            'pekerjaan' => 'required|string|max:255',
            'petugas' => 'required|string|max:100',
            'paraf' => 'required|string|max:300000',
        ], [
            'paraf.required' => 'Paraf wajib diisi (gambar di kotak paraf).',
        ]);

        // Paraf harus benar-benar gambar PNG.
        $prefix = 'data:image/png;base64,';
        $biner = str_starts_with($data['paraf'], $prefix) ? base64_decode(substr($data['paraf'], strlen($prefix)), true) : false;
        if ($biner === false || !str_starts_with($biner, "\x89PNG")) {
            throw ValidationException::withMessages(['paraf' => 'Paraf tidak valid. Silakan gambar ulang.']);
        }

        $catatan = LogbookPeminjaman::create([
            'alat_id' => $alat->alat_id,
            'tanggal' => $data['tanggal'],
            'rencana_kembali' => $data['rencana_kembali'] ?? null,
            'pekerjaan' => $data['pekerjaan'],
            'petugas' => $data['petugas'],
            'paraf' => $data['paraf'],
            'dibuat_oleh' => Auth::id(),
            'status' => 'diajukan',
        ]);

        $this->notifKoordinator("Peminjaman {$alat->nama_alat} oleh {$data['petugas']} ({$data['pekerjaan']}) menunggu persetujuan.", route('peminjaman.alat', $alat->alat_id));

        return redirect()->route('peminjaman.alat', $alat->alat_id)->with('success', 'Catatan peminjaman tersimpan dan menunggu persetujuan Koordinator.');
    }

    public function approve(Request $request, $id)
    {
        abort_unless($this->pengelola(), 403);

        $c = LogbookPeminjaman::findOrFail($id);
        abort_unless($c->status === 'diajukan', 422, 'Status catatan sudah berubah.');
        $c->update(['status' => 'disetujui', 'disetujui_oleh' => Auth::id(), 'tgl_keputusan' => now()]);

        $this->notifUser($c->dibuat_oleh, 'Peminjaman alat Anda disetujui Koordinator.', route('peminjaman.alat', $c->alat_id));

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function reject(Request $request, $id)
    {
        abort_unless($this->pengelola(), 403);
        $data = $request->validate(['catatan_approval' => 'required|string|max:500']);

        $c = LogbookPeminjaman::findOrFail($id);
        abort_unless($c->status === 'diajukan', 422, 'Status catatan sudah berubah.');
        $c->update(['status' => 'ditolak', 'disetujui_oleh' => Auth::id(), 'tgl_keputusan' => now(), 'catatan_approval' => $data['catatan_approval']]);

        $this->notifUser($c->dibuat_oleh, 'Peminjaman alat Anda ditolak: ' . $data['catatan_approval'], route('peminjaman.alat', $c->alat_id));

        return back()->with('success', 'Peminjaman ditolak.');
    }

    /**
     * Mencatat alat sudah dikembalikan: mengisi tanggal kembali dan mengubah status menjadi "dikembalikan".
     * - Peminjam (pembuat catatan): sekali, saat status masih "disetujui".
     * - Koordinator: boleh mencatat, dan mengoreksi tanggalnya SEKALI saja setelah status "dikembalikan".
     */
    public function kembali(Request $request, $id)
    {
        $c = LogbookPeminjaman::findOrFail($id);
        $pengelola = $this->pengelola();

        abort_unless($c->dibuat_oleh === Auth::id() || $pengelola, 403);
        abort_unless(in_array($c->status, ['disetujui', 'dikembalikan'], true), 422, 'Pengembalian hanya dapat dicatat setelah peminjaman disetujui Koordinator.');
        abort_if($c->status === 'dikembalikan' && !$pengelola, 403, 'Pengembalian sudah tercatat. Hubungi Koordinator jika perlu dikoreksi.');
        abort_if($c->status === 'dikembalikan' && $c->dikoreksi_pada !== null, 422, 'Tanggal pengembalian sudah pernah dikoreksi dan tidak dapat diubah lagi.');

        $data = $request->validate([
            'tanggal_kembali' => 'required|date|after_or_equal:' . $c->tanggal->toDateString(),
        ], [
            'tanggal_kembali.after_or_equal' => 'Tanggal dikembalikan tidak boleh sebelum tanggal peminjaman.',
        ]);

        $koreksi = $c->status === 'dikembalikan';
        $c->update([
            'tanggal_kembali' => $data['tanggal_kembali'],
            'status' => 'dikembalikan',
            'dikoreksi_pada' => $koreksi ? now() : null,
        ]);

        if (!$koreksi && !$pengelola) {
            $this->notifKoordinator("{$c->alat->nama_alat} telah dikembalikan oleh {$c->petugas} ({$c->pekerjaan}).", route('peminjaman.alat', $c->alat_id));
        }

        return back()->with('success', $koreksi ? 'Tanggal pengembalian dikoreksi.' : 'Alat dicatat sudah dikembalikan.');
    }

    /** Pembuat boleh menarik catatannya selama belum disetujui. */
    public function batal($id)
    {
        $c = LogbookPeminjaman::findOrFail($id);
        abort_unless($c->dibuat_oleh === Auth::id(), 403);
        abort_unless($c->status === 'diajukan', 422, 'Catatan yang sudah diputuskan tidak dapat ditarik.');
        $c->delete();

        return back()->with('success', 'Catatan dibatalkan.');
    }

    /** Koordinator boleh menghapus catatan yang DITOLAK saja (soft delete, tetap tercatat di Audit Trail). */
    public function hapus($id)
    {
        abort_unless($this->pengelola(), 403);

        $c = LogbookPeminjaman::findOrFail($id);
        abort_unless($c->status === 'ditolak', 422, 'Hanya catatan yang ditolak yang dapat dihapus.');
        $c->delete();

        return back()->with('success', 'Catatan yang ditolak dihapus.');
    }

    // ------------------------------------------------------------ PDF

    private function bagianPdf(Alat $alat): array
    {
        return [
            'alat' => $alat,
            'baris' => LogbookPeminjaman::with(['penyetuju.personil'])
                ->where('alat_id', $alat->alat_id)->where('status', '!=', 'ditolak')
                ->orderBy('tanggal')->orderBy('logbook_peminjaman_id')->get(),
        ];
    }

    public function pdf($id)
    {
        $alat = $this->alatBisaDipinjam()->findOrFail($id);

        return Pdf::loadView('peminjaman.logbook-pdf', ['bagian' => [$this->bagianPdf($alat)], 'dicetakOleh' => $this->namaUser()])
            ->setPaper('a4', 'portrait')
            ->stream('Logbook_' . $alat->nama_alat . '_' . $alat->kode_alat . '.pdf');
    }

    public function pdfSemua()
    {
        $bagian = $this->alatBisaDipinjam()->orderBy('nama_alat')->get()->map(fn($a) => $this->bagianPdf($a))->all();

        return Pdf::loadView('peminjaman.logbook-pdf', ['bagian' => $bagian, 'dicetakOleh' => $this->namaUser()])
            ->setPaper('a4', 'portrait')
            ->stream('Logbook_Semua_Alat.pdf');
    }

    // ------------------------------------------------------------ notifikasi

    private function notifKoordinator(string $pesan, string $url): void
    {
        $ids = User::whereHas('role', fn($q) => $q->whereIn('nama_role', PeranPengguna::pengelolaPeminjaman()))
            ->where('status_aktif', true)->pluck('users_id');
        foreach ($ids as $uid) {
            $this->notifUser($uid, $pesan, $url);
        }
    }

    private function notifUser(int $userId, string $pesan, string $url): void
    {
        DB::table('notifikasi')->insert([
            'users_id' => $userId, 'jenis_notifikasi' => 'peminjaman', 'pesan' => $pesan,
            'url' => $url, 'is_read' => false, 'created_at' => now(),
        ]);
    }
}