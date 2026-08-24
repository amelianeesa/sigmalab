<?php

namespace App\Http\Controllers;

use App\Models\RiwayatTindakLanjut;
use App\Models\HasilUji;
use App\Models\TindakLanjutKomentar;
use App\Models\User;
use App\Enums\PeranPengguna;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RiwayatTindakLanjutController extends Controller
{
    use AuthorizesRequests;

    protected $notificationService;

    public function __construct(\App\Services\NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', RiwayatTindakLanjut::class);

        $filterStatus = $request->input('filter_status');

        $query = RiwayatTindakLanjut::with(['hasilUji.parameterUji', 'hasilUji.kegiatan', 'penindaklanjut']);

        if ($filterStatus) {
            $query->where('status_tindak_lanjut', $filterStatus);
        }

        $riwayat = $query->latest('created_at')->paginate(10);

        return view('tindak-lanjut.index', compact('riwayat', 'filterStatus'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', RiwayatTindakLanjut::class);

        $hasilUjiOutlier = HasilUji::where('status_berketerimaan', 'outlier')
            ->with(['parameterUji', 'kegiatan'])
            ->get();

        $selectedHasilUji = $request->input('hasil_uji_id');

        return view('tindak-lanjut.create', compact('hasilUjiOutlier', 'selectedHasilUji'));
    }

    public function store(\App\Http\Requests\TindakLanjutRequest $request)
    {
        $this->authorize('create', RiwayatTindakLanjut::class);

        $validated = $request->validated();

        RiwayatTindakLanjut::create([
            'hasil_uji_id' => $validated['hasil_uji_id'],
            'status_tindak_lanjut' => $validated['status_tindak_lanjut'],
            'catatan_investigasi' => $validated['catatan_investigasi'],
            'ditindaklanjuti_oleh' => Auth::id(),
            'created_at' => now(),
        ]);

        return redirect()->route('tindak-lanjut.index')->with('success', 'Riwayat tindak lanjut berhasil dicatat.');
    }

    public function show($id)
    {
        $tindakLanjut = RiwayatTindakLanjut::findOrFail($id);
        $this->authorize('view', $tindakLanjut);

        $tindakLanjut->load(['hasilUji.parameterUji', 'hasilUji.kegiatan', 'penindaklanjut', 'komentars.user']);

        return view('tindak-lanjut.show', compact('tindakLanjut'));
    }

    public function edit($id)
    {
        $tindakLanjut = RiwayatTindakLanjut::findOrFail($id);
        $this->authorize('update', $tindakLanjut);

        $tindakLanjut->load(['hasilUji.parameterUji', 'hasilUji.kegiatan']);

        return view('tindak-lanjut.edit', compact('tindakLanjut'));
    }

    public function update(\App\Http\Requests\TindakLanjutRequest $request, $id)
    {
        $tindakLanjut = RiwayatTindakLanjut::findOrFail($id);
        $this->authorize('update', $tindakLanjut);

        $validated = $request->validated();

        $tindakLanjut->update([
            'status_tindak_lanjut' => $validated['status_tindak_lanjut'],
            'catatan_investigasi' => $validated['catatan_investigasi'],
            'ditindaklanjuti_oleh' => Auth::id(),
        ]);

        return redirect()->route('tindak-lanjut.show', $tindakLanjut->riwayat_tindak_lanjut_id)
            ->with('success', 'Tindak lanjut investigasi berhasil diperbarui.');
    }

    public function storeKomentar(Request $request, $id)
    {
        $tindakLanjut = RiwayatTindakLanjut::findOrFail($id);
        $this->authorize('view', $tindakLanjut); // Analis can view and comment

        $validated = $request->validate([
            'komentar' => 'required|string|max:1000',
        ]);

        TindakLanjutKomentar::create([
            'riwayat_tindak_lanjut_id' => $tindakLanjut->riwayat_tindak_lanjut_id,
            'users_id' => Auth::id(),
            'komentar' => $validated['komentar'],
        ]);

        // Send notifications via Service
        $currentUser = Auth::user();
        $senderName = $currentUser->personil->nama_personil ?? $currentUser->username;
        $pesanNotif = "💬 Komentar baru dari {$senderName} pada Tindak Lanjut Outlier #" . $tindakLanjut->riwayat_tindak_lanjut_id;

        $this->notificationService->notifyRoles($pesanNotif, 'qc', [
            PeranPengguna::ANALIS->value,
            PeranPengguna::KOORDINATOR_LAB->value,
        ], [$currentUser->users_id]);

        return redirect()->route('tindak-lanjut.show', $tindakLanjut->riwayat_tindak_lanjut_id)
            ->with('success', 'Komentar berhasil ditambahkan.');
    }
}
