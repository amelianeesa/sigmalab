<?php

namespace App\Http\Controllers;

use App\Models\RiwayatTindakLanjut;
use App\Models\HasilUji;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class RiwayatTindakLanjutController extends Controller
{
    use AuthorizesRequests;

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

        // List hasil uji outlier yang bisa ditindaklanjuti
        $hasilUjiOutlier = HasilUji::where('status_berketerimaan', 'outlier')
            ->with(['parameterUji', 'kegiatan'])
            ->get();

        $selectedHasilUji = $request->input('hasil_uji_id');

        return view('tindak-lanjut.create', compact('hasilUjiOutlier', 'selectedHasilUji'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', RiwayatTindakLanjut::class);

        $validated = $request->validate([
            'hasil_uji_id' => 'required|exists:hasil_uji,hasil_uji_id',
            'status_tindak_lanjut' => 'required|in:belum_ditindaklanjuti,dalam_investigasi,selesai',
            'catatan_investigasi' => 'nullable|string',
        ]);

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

        $tindakLanjut->load(['hasilUji.parameterUji', 'hasilUji.kegiatan', 'penindaklanjut']);

        return view('tindak-lanjut.show', compact('tindakLanjut'));
    }

    // Tidak ada edit, update, destroy — insert only
}
