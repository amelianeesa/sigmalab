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


class LogbookController extends Controller
{
    private function pengelola(): bool
    {
        return in_array(Auth::user()->role?->nama_role, PeranPengguna::pengelolaPeminjaman(), true);
    }

    private function bisaMengisi(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $role = $user->role?->nama_role ?? '';
        $tidakMengisi = array(
            PeranPengguna::HR_OFFICER->value, 
            PeranPengguna::GA_OFFICER->value
        );
        if (in_array($role, $tidakMengisi, true)) {
            return false;
        }

        return $user->hasModulAccess('peminjaman', 'tambah_ubah');
    }

    private function namaUser(): string
    {
        return Auth::user()->personil->nama ?? Auth::user()->username;
    }

    private function alatBisaDipinjam()
    {
        return Alat::where('dapat_dipinjam', true);
    }

    public function index(Request $request)
    {
        $cari = trim((string) $request->input('search', ''));

        $alatList = $this->alatBisaDipinjam()
            ->when($cari !== '', function ($q) use ($cari) {
                $like = '%' . addcslashes($cari, '%_\\') . '%';
                $q->where(fn($w) => $w->where('nama_alat', 'like', $like)->orWhere('kode_alat', 'like', $like));
            })
            ->orderBy('nama_alat')->paginate(12)->withQueryString();

        $ringkas = LogbookPeminjaman::whereIn('alat_id', $alatList->pluck('alat_id'))
            ->where('status', '!=', 'ditolak')
            ->selectRaw("alat_id, count(*) as total, sum(status = 'diajukan') as menunggu")
            ->groupBy('alat_id')->get()->keyBy('alat_id');

        $kolom = ['logbook_peminjaman_id', 'alat_id', 'tanggal', 'rencana_kembali', 'pekerjaan', 'petugas', 'status'];
        $idAlat = $alatList->pluck('alat_id');

        $sedangDipinjam = LogbookPeminjaman::select($kolom)->whereIn('alat_id', $idAlat)
            ->where('status', 'disetujui')
            ->orderByDesc('tanggal')->orderByDesc('logbook_peminjaman_id')
            ->get()->unique('alat_id')->keyBy('alat_id');

        $terakhir = LogbookPeminjaman::select($kolom)->whereIn('alat_id', $idAlat)
            ->where('status', '!=', 'ditolak')
            ->orderByDesc('tanggal')->orderByDesc('logbook_peminjaman_id')
            ->get()->unique('alat_id')->keyBy('alat_id');

        return view('peminjaman.index', compact('alatList', 'ringkas', 'cari', 'sedangDipinjam', 'terakhir'));
    }

    public function show(Request $request, $id)
    {
        $alat = $this->alatBisaDipinjam()->findOrFail($id);
        $cari = trim((string) $request->input('search', ''));
        $perHalaman = 10;

        $query = LogbookPeminjaman::with(['pembuat.personil', 'penyetuju.personil'])
            ->where('alat_id', $alat->alat_id)
            ->when($cari !== '', function ($q) use ($cari) {
                $like = '%' . addcslashes($cari, '%_\\') . '%';
                $q->where(fn($w) => $w->where('pekerjaan', 'like', $like)->orWhere('petugas', 'like', $like));
            })
            ->orderBy('tanggal')->orderBy('logbook_peminjaman_id');

        // Bila halaman tidak ditentukan, buka halaman terakhir agar catatan terbaru langsung terlihat.
        $halaman = $request->filled('page')
            ? max(1, (int) $request->input('page'))
            : max(1, (int) ceil((clone $query)->count() / $perHalaman));

        $baris = $query->paginate($perHalaman, ['*'], 'page', $halaman)->withQueryString();

        return view('peminjaman.logbook', [
            'alat' => $alat,
            'baris' => $baris,
            'cari' => $cari,
            'bisaMengisi' => $this->bisaMengisi(),
            'pengelola' => $this->pengelola(),
            'namaDefault' => $this->namaUser(),
        ]);
    }


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

    public function batal($id)
    {
        $c = LogbookPeminjaman::findOrFail($id);
        abort_unless($c->dibuat_oleh === Auth::id(), 403);
        abort_unless($c->status === 'diajukan', 422, 'Catatan yang sudah diputuskan tidak dapat ditarik.');
        $c->delete();

        return back()->with('success', 'Catatan dibatalkan.');
    }

    public function hapus($id)
    {
        abort_unless($this->pengelola(), 403);

        $c = LogbookPeminjaman::findOrFail($id);
        abort_unless($c->status === 'ditolak', 422, 'Hanya catatan yang ditolak yang dapat dihapus.');
        $c->delete();

        return back()->with('success', 'Catatan yang ditolak dihapus.');
    }


    private function bagianPdf(Alat $alat): array
    {
        $baris = LogbookPeminjaman::with(['penyetuju.personil'])
            ->where('alat_id', $alat->alat_id)->where('status', '!=', 'ditolak')
            ->orderBy('tanggal')->orderBy('logbook_peminjaman_id')->get();

        foreach ($baris as $r) {
            $r->paraf_pdf = $this->siapkanParafPdf($r->paraf);
        }

        return ['alat' => $alat, 'baris' => $baris];
    }

    private function siapkanParafPdf(?string $dataUri, int $maxW = 44, int $maxH = 23): ?array
    {
        if (!$dataUri) {
            return null;
        }

        $asli = ['src' => $dataUri, 'w' => null, 'h' => null];
        $prefix = 'data:image/png;base64,';

        if (!function_exists('imagecreatefromstring') || !str_starts_with($dataUri, $prefix)) {
            return $asli;
        }

        try {
            $biner = base64_decode(substr($dataUri, strlen($prefix)), true);
            $src = $biner ? @imagecreatefromstring($biner) : false;
            if (!$src) {
                return $asli;
            }
            if (!imageistruecolor($src)) {
                imagepalettetotruecolor($src);
            }

            $w = imagesx($src);
            $h = imagesy($src);
            $minX = $w; $minY = $h; $maxX = -1; $maxY = -1;

            // Cari kotak terkecil yang memuat semua piksel yang tidak transparan.
            for ($y = 0; $y < $h; $y++) {
                for ($x = 0; $x < $w; $x++) {
                    $alpha = (imagecolorat($src, $x, $y) >> 24) & 0x7F; // 0 = solid, 127 = transparan
                    if ($alpha < 127) {
                        if ($x < $minX) $minX = $x;
                        if ($x > $maxX) $maxX = $x;
                        if ($y < $minY) $minY = $y;
                        if ($y > $maxY) $maxY = $y;
                    }
                }
            }

            if ($maxX < 0) {
                return $asli; 
            }

            $pad = 3;
            $x0 = max(0, $minX - $pad);
            $y0 = max(0, $minY - $pad);
            $cw = min($w, $maxX + $pad + 1) - $x0;
            $ch = min($h, $maxY + $pad + 1) - $y0;

            $dst = imagecreatetruecolor($cw, $ch);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagefilledrectangle($dst, 0, 0, $cw, $ch, imagecolorallocatealpha($dst, 255, 255, 255, 127));
            imagecopy($dst, $src, 0, 0, $x0, $y0, $cw, $ch);

            ob_start();
            imagepng($dst);
            $png = ob_get_clean();

            $skala = min($maxW / $cw, $maxH / $ch);

            return [
                'src' => $prefix . base64_encode($png),
                'w' => max(1, (int) round($cw * $skala)),
                'h' => max(1, (int) round($ch * $skala)),
            ];
        } catch (\Throwable $e) {
            return $asli;
        }
    }

    public function pdf($id)
    {
        $alat = $this->alatBisaDipinjam()->findOrFail($id);

        return Pdf::loadView('peminjaman.logbook-pdf', ['bagian' => [$this->bagianPdf($alat)], 'dicetakOleh' => $this->namaUser()])
            ->setPaper('a4', 'portrait')
            ->stream('Logbook_Peminjaman_' . trim(preg_replace('/[^\w\-]+/u', '_', $alat->nama_alat . '_' . $alat->kode_alat), '_') . '.pdf');
    }

    public function pdfSemua()
    {
        $bagian = $this->alatBisaDipinjam()->orderBy('nama_alat')->get()->map(fn($a) => $this->bagianPdf($a))->all();

        return Pdf::loadView('peminjaman.logbook-pdf', ['bagian' => $bagian, 'dicetakOleh' => $this->namaUser()])
            ->setPaper('a4', 'portrait')
            ->stream('Logbook_Peminjaman_Semua_Alat.pdf');
    }


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