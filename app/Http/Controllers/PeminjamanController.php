<?php

namespace App\Http\Controllers;

use App\Enums\PeranPengguna;
use App\Models\Alat;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeminjamanController extends Controller
{
    // ------------------------------------------------------------------ helper

    private function pengelola(): bool
    {
        return in_array(Auth::user()->role?->nama_role, PeranPengguna::pengelolaPeminjaman(), true);
    }

    private function bisaAjukan(): bool
    {
        // Koordinator (penyetuju), HR, dan GA hanya memantau; yang mengajukan adalah peminjam.
        $role = Auth::user()->role?->nama_role;
        if (in_array($role, array_merge(PeranPengguna::pengelolaPeminjaman(), [PeranPengguna::HR_OFFICER->value, PeranPengguna::GA_OFFICER->value]), true)) {
            return false;
        }

        return Auth::user()->hasModulAccess('peminjaman', 'tambah_ubah');
    }

    private function boleh(Peminjaman $p): void
    {
        // Pengelola & role "lihat" boleh membuka semua; peminjam biasa hanya miliknya.
        $lihatSemua = $this->pengelola() || !$this->bisaAjukan();
        abort_unless($lihatSemua || $p->peminjam_id === Auth::id(), 403);
    }

    /** ID alat yang tidak bisa dipinjam pada rentang tanggal tertentu. */
    private function alatBentrok(string $mulai, string $selesai, ?int $kecualiPeminjamanId = null): array
    {
        return PeminjamanItem::query()
            ->whereHas('peminjaman', function ($q) use ($mulai, $selesai, $kecualiPeminjamanId) {
                $q->when($kecualiPeminjamanId, fn($x) => $x->where('peminjaman_id', '!=', $kecualiPeminjamanId))
                  ->where(function ($w) use ($mulai, $selesai) {
                      $w->where('status', 'dipinjam') // barang sedang di luar
                        ->orWhere(fn($o) => $o->where('status', 'disetujui')
                            ->where('tgl_pinjam', '<=', $selesai)
                            ->where('tgl_rencana_kembali', '>=', $mulai));
                  });
            })
            ->pluck('alat_id')->all();
    }

    private function notif(iterable $userIds, string $pesan, string $url): void
    {
        $rows = [];
        foreach ($userIds as $id) {
            $rows[] = [
                'users_id' => $id, 'jenis_notifikasi' => 'peminjaman', 'pesan' => $pesan,
                'url' => $url, 'is_read' => false, 'created_at' => now(),
            ];
        }
        if ($rows) {
            DB::table('notifikasi')->insert($rows);
        }
    }

    private function idPengelola(): array
    {
        return User::whereHas('role', fn($q) => $q->whereIn('nama_role', PeranPengguna::pengelolaPeminjaman()))
            ->where('status_aktif', true)->pluck('users_id')->all();
    }

    // ------------------------------------------------------------------ halaman

    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = trim((string) $request->input('search', ''));
        $lihatSemua = $this->pengelola() || !$this->bisaAjukan();

        $daftar = Peminjaman::with(['peminjam.personil', 'items.alat'])
            ->when(!$lihatSemua, fn($q) => $q->where('peminjam_id', Auth::id()))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $q->where(function ($w) use ($like) {
                    $w->where('kode', 'like', $like)
                      ->orWhere('tujuan', 'like', $like)
                      ->orWhereHas('items.alat', fn($a) => $a->where('nama_alat', 'like', $like)->orWhere('kode_alat', 'like', $like))
                      ->orWhereHas('peminjam', fn($u) => $u->where('username', 'like', $like)
                          ->orWhereHas('personil', fn($ps) => $ps->where('nama', 'like', $like)));
                });
            })
            ->latest()
            ->paginate(10)->withQueryString();

        return view('peminjaman.index', [
            'daftar' => $daftar,
            'status' => $status,
            'search' => $search,
            'lihatSemua' => $lihatSemua,
            'bisaAjukan' => $this->bisaAjukan(),
        ]);
    }

    public function create()
    {
        abort_unless($this->bisaAjukan(), 403);

        $kunciSekarang = PeminjamanItem::whereHas('peminjaman', fn($q) => $q->where('status', 'dipinjam'))
            ->pluck('alat_id')->all();

        $induk = Alat::with(['subAlat' => fn($q) => $q->where('dapat_dipinjam', true)->where('kondisi_barang', 'baik')])
            ->whereNull('parent_id')
            ->where(function ($q) {
                // induk tampil bila dirinya boleh dipinjam, atau punya sub alat yang boleh dipinjam
                $q->where('dapat_dipinjam', true)->orWhereHas('subAlat', fn($s) => $s->where('dapat_dipinjam', true));
            })
            ->orderBy('nama_alat')->get();

        return view('peminjaman.create', compact('induk', 'kunciSekarang'));
    }

    public function store(Request $request)
    {
        abort_unless($this->bisaAjukan(), 403);

        $data = $request->validate([
            'tujuan' => 'required|string|max:255',
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'tgl_rencana_kembali' => 'required|date|after_or_equal:tgl_pinjam',
            'alat_ids' => 'required|array|min:1',
            'alat_ids.*' => 'integer',
        ], [
            'alat_ids.required' => 'Pilih minimal satu alat.',
        ]);

        // Hanya alat yang memang boleh dipinjam di workspace ini (scope Alat otomatis NON_LAB).
        $alat = Alat::whereIn('alat_id', $data['alat_ids'])
            ->where('dapat_dipinjam', true)->where('kondisi_barang', 'baik')->get();

        if ($alat->count() !== count(array_unique($data['alat_ids']))) {
            throw ValidationException::withMessages(['alat_ids' => 'Ada alat yang tidak tersedia untuk dipinjam.']);
        }

        $bentrok = array_intersect($alat->pluck('alat_id')->all(), $this->alatBentrok($data['tgl_pinjam'], $data['tgl_rencana_kembali']));
        if ($bentrok) {
            $nama = $alat->whereIn('alat_id', $bentrok)->pluck('nama_alat')->implode(', ');
            throw ValidationException::withMessages(['alat_ids' => "Sudah dipesan/dipinjam pada tanggal tersebut: {$nama}."]);
        }

        $p = DB::transaction(function () use ($data, $alat) {
            $urut = Peminjaman::withTrashed()->whereDate('created_at', today())->count() + 1;

            $p = Peminjaman::create([
                'kode' => 'PJM-' . now()->format('Ymd') . '-' . str_pad($urut, 3, '0', STR_PAD_LEFT),
                'peminjam_id' => Auth::id(),
                'tujuan' => $data['tujuan'],
                'tgl_pinjam' => $data['tgl_pinjam'],
                'tgl_rencana_kembali' => $data['tgl_rencana_kembali'],
                'status' => 'diajukan',
            ]);

            foreach ($alat as $a) {
                $p->items()->create(['alat_id' => $a->alat_id, 'kondisi_keluar' => $a->kondisi_barang]);
            }

            return $p;
        });

        $nama = Auth::user()->personil->nama ?? Auth::user()->username;
        $this->notif($this->idPengelola(), "Pengajuan peminjaman alat {$p->kode} dari {$nama} menunggu persetujuan.", route('peminjaman.show', $p->peminjaman_id));

        return redirect()->route('peminjaman.show', $p->peminjaman_id)->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    public function show($id)
    {
        $p = Peminjaman::with(['peminjam.personil', 'penyetuju.personil', 'items.alat'])->findOrFail($id);
        $this->boleh($p);

        return view('peminjaman.show', ['p' => $p, 'pengelola' => $this->pengelola()]);
    }

    // ------------------------------------------------------------------ alur status

    public function approve(Request $request, $id)
    {
        abort_unless($this->pengelola(), 403);
        $data = $request->validate(['catatan_approval' => 'nullable|string|max:500']);

        DB::transaction(function () use ($id, $data, &$p) {
            $p = Peminjaman::with('items')->lockForUpdate()->findOrFail($id);
            abort_unless($p->status === 'diajukan', 422, 'Status pengajuan sudah berubah.');

            $bentrok = array_intersect(
                $p->items->pluck('alat_id')->all(),
                $this->alatBentrok($p->tgl_pinjam->toDateString(), $p->tgl_rencana_kembali->toDateString(), $p->peminjaman_id)
            );
            if ($bentrok) {
                throw ValidationException::withMessages(['catatan_approval' => 'Ada alat yang sudah dipesan/dipinjam pada tanggal tersebut.']);
            }

            $p->update([
                'status' => 'disetujui', 'disetujui_oleh' => Auth::id(),
                'tgl_keputusan' => now(), 'catatan_approval' => $data['catatan_approval'] ?? null,
            ]);
        });

        $this->notif([$p->peminjam_id], "Peminjaman {$p->kode} disetujui. Silakan ambil alat sesuai jadwal.", route('peminjaman.show', $p->peminjaman_id));

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function reject(Request $request, $id)
    {
        abort_unless($this->pengelola(), 403);
        $data = $request->validate(['catatan_approval' => 'required|string|max:500']);

        $p = Peminjaman::findOrFail($id);
        abort_unless($p->status === 'diajukan', 422, 'Status pengajuan sudah berubah.');

        $p->update([
            'status' => 'ditolak', 'disetujui_oleh' => Auth::id(),
            'tgl_keputusan' => now(), 'catatan_approval' => $data['catatan_approval'],
        ]);

        $this->notif([$p->peminjam_id], "Peminjaman {$p->kode} ditolak: {$data['catatan_approval']}", route('peminjaman.show', $p->peminjaman_id));

        return back()->with('success', 'Peminjaman ditolak.');
    }

    /** Alat diserahkan ke peminjam. */
    public function serahkan($id)
    {
        abort_unless($this->pengelola(), 403);

        DB::transaction(function () use ($id) {
            $p = Peminjaman::with('items.alat')->lockForUpdate()->findOrFail($id);
            abort_unless($p->status === 'disetujui', 422, 'Peminjaman belum disetujui.');

            $sedangKeluar = PeminjamanItem::whereIn('alat_id', $p->items->pluck('alat_id'))
                ->whereHas('peminjaman', fn($q) => $q->where('status', 'dipinjam'))->exists();
            if ($sedangKeluar) {
                throw ValidationException::withMessages(['status' => 'Ada alat yang belum dikembalikan peminjam lain.']);
            }

            foreach ($p->items as $item) {
                $item->update(['kondisi_keluar' => $item->alat->kondisi_barang ?? 'baik']);
                $item->alat?->update(['status_barang' => 'terpakai']);
            }
            $p->update(['status' => 'dipinjam']);
        });

        return back()->with('success', 'Alat ditandai sudah diserahkan ke peminjam.');
    }

    /** Alat dikembalikan; kondisi per item dicatat. */
    public function kembalikan(Request $request, $id)
    {
        abort_unless($this->pengelola(), 403);

        $data = $request->validate([
            'kondisi' => 'required|array',
            'kondisi.*' => 'required|in:baik,rusak,perbaikan',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($id, $data) {
            $p = Peminjaman::with('items.alat')->lockForUpdate()->findOrFail($id);
            abort_unless($p->status === 'dipinjam', 422, 'Alat belum berstatus dipinjam.');

            foreach ($p->items as $item) {
                $kondisi = $data['kondisi'][$item->peminjaman_item_id] ?? 'baik';
                $item->update([
                    'kondisi_kembali' => $kondisi,
                    'catatan_kembali' => $data['catatan'][$item->peminjaman_item_id] ?? null,
                ]);
                $item->alat?->update([
                    'status_barang' => 'idle',
                    'kondisi_barang' => $kondisi, // alat rusak otomatis tidak bisa dipinjam lagi
                ]);
            }

            $p->update(['status' => 'dikembalikan', 'tgl_kembali_aktual' => today()]);
        });

        return back()->with('success', 'Pengembalian alat dicatat.');
    }

    public function batal($id)
    {
        $p = Peminjaman::findOrFail($id);
        abort_unless($p->peminjam_id === Auth::id() || $this->pengelola(), 403);
        abort_unless(in_array($p->status, ['diajukan', 'disetujui'], true), 422, 'Peminjaman tidak dapat dibatalkan.');

        $p->update(['status' => 'dibatalkan']);

        return back()->with('success', 'Peminjaman dibatalkan.');
    }

    // ------------------------------------------------------------------ logbook (pengganti buku catatan manual)

    private function dataLogbook(Request $request, $id): array
    {
        $alat = Alat::findOrFail($id);
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');

        $baris = PeminjamanItem::with(['peminjaman.peminjam.personil'])
            ->where('alat_id', $alat->alat_id)
            ->whereHas('peminjaman', function ($q) use ($dari, $sampai) {
                $q->whereIn('status', ['dipinjam', 'dikembalikan'])
                  ->when($dari, fn($x) => $x->whereDate('tgl_pinjam', '>=', $dari))
                  ->when($sampai, fn($x) => $x->whereDate('tgl_pinjam', '<=', $sampai));
            })
            ->get()
            ->sortBy(fn($i) => $i->peminjaman->tgl_pinjam->timestamp . '-' . $i->peminjaman_id)
            ->values();

        return compact('alat', 'baris', 'dari', 'sampai');
    }

    public function logbookIndex()
    {
        $alatList = Alat::where('dapat_dipinjam', true)->orderBy('nama_alat')->get();

        $jumlah = PeminjamanItem::whereIn('alat_id', $alatList->pluck('alat_id'))
            ->whereHas('peminjaman', fn($q) => $q->whereIn('status', ['dipinjam', 'dikembalikan']))
            ->selectRaw('alat_id, count(*) as total')->groupBy('alat_id')->pluck('total', 'alat_id');

        return view('peminjaman.logbook-index', compact('alatList', 'jumlah'));
    }

    public function logbook(Request $request, $id)
    {
        return view('peminjaman.logbook', $this->dataLogbook($request, $id));
    }

    public function logbookPdf(Request $request, $id)
    {
        $data = $this->dataLogbook($request, $id);
        $data['dicetakOleh'] = Auth::user()->personil->nama ?? Auth::user()->username;

        return Pdf::loadView('peminjaman.logbook-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->stream('Logbook_' . $data['alat']->nama_alat . '_' . $data['alat']->kode_alat . '.pdf');
    }
}
