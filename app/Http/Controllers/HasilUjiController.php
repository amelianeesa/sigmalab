<?php

namespace App\Http\Controllers;

use App\Models\HasilUji;
use App\Models\Kegiatan;
use App\Models\ParameterUji;
use App\Models\User;
use App\Services\WestgardService;
use App\Enums\PeranPengguna;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\CalculationService;
use App\Services\HasilUjiService;
use App\Services\ChartDataService;

class HasilUjiController extends Controller
{
    use AuthorizesRequests;

    protected WestgardService $westgardService;
    protected CalculationService $calculationService;
    protected HasilUjiService $hasilUjiService;
    protected ChartDataService $chartDataService;

    public function __construct(
        CalculationService $calculationService, 
        WestgardService $westgardService,
        HasilUjiService $hasilUjiService,
        ChartDataService $chartDataService
    ) {
        $this->calculationService = $calculationService;
        $this->westgardService = $westgardService;
        $this->hasilUjiService = $hasilUjiService;
        $this->chartDataService = $chartDataService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', HasilUji::class);

        $filterKegiatan = $request->input('filter_kegiatan');
        $filterStatus = $request->input('filter_status');

        $query = HasilUji::with(['kegiatan', 'parameterUji', 'penginput']);

        if ($filterKegiatan) {
            $query->where('kegiatan_id', $filterKegiatan);
        }

        if ($filterStatus) {
            $query->where('status_berketerimaan', $filterStatus);
        }

        $hasilUjiList = $query->latest('created_at')->paginate(10);
        $kegiatanList = Kegiatan::all();

        return view('hasil-uji.index', compact('hasilUjiList', 'kegiatanList', 'filterKegiatan', 'filterStatus'));
    }

    public function edit($id)
    {
        $hasilUji = HasilUji::findOrFail($id);
        $this->authorize('update', $hasilUji);

        if (in_array($hasilUji->kegiatan->status_kegiatan, ['selesai', 'dibatalkan'])) {
            return redirect()->route('kegiatan.show', $hasilUji->kegiatan_id)
                ->with('error', 'Kegiatan sudah selesai atau dibatalkan.');
        }

        $hasilUji->load(['kegiatan', 'parameterUji']);

        return view('hasil-uji.edit', compact('hasilUji'));
    }

    public function retest(Request $request, $id)
    {
        $this->authorize('create', HasilUji::class);

        $oldHasilUji = HasilUji::findOrFail($id);
        
        // Pastikan hanya bisa retest jika status sebelumnya adalah outlier atau ditolak
        if (!in_array(strtolower($oldHasilUji->status_berketerimaan), ['outlier', 'ditolak'])) {
            return back()->with('error', 'Hanya data yang berstatus Outlier yang dapat diuji ulang.');
        }
        
        // Cek max run (misal max 3x uji ulang)
        if ($oldHasilUji->run_ke >= 3) {
            return back()->with('error', 'Batas maksimal uji ulang (Run 3) telah tercapai untuk parameter ini.');
        }

        DB::transaction(function () use ($oldHasilUji) {
            $newHasil = $oldHasilUji->replicate(['data_mentah', 'nilai_hasil', 'z_score', 'kode_aturan_dilanggar', 'override_status', 'override_kode', 'keterangan_override']);
            
            $newHasil->run_ke = $oldHasilUji->run_ke + 1;
            $newHasil->status_berketerimaan = 'belum_diuji';
            $newHasil->diinput_oleh = Auth::id();
            $newHasil->save();
        });

        return redirect()->route('kegiatan.show', $oldHasilUji->kegiatan_id)
            ->with('success', 'Baris Uji Ulang (Run ' . ($oldHasilUji->run_ke + 1) . ') berhasil dibuat. Silakan input data pengujian.');
    }

    public function update(Request $request, $id)
    {
        $this->authorize('create', HasilUji::class);

        $hasilUji = HasilUji::findOrFail($id);

        if (in_array($hasilUji->kegiatan->status_kegiatan, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Tidak dapat menginput hasil uji karena kegiatan sudah selesai atau dibatalkan.');
        }

        if ($request->has('runs')) {
            $runsData = $request->input('runs');
            $currentHasil = $hasilUji;
            $totalRuns = count($runsData);
            $lastResult = null;
            
            foreach ($runsData as $index => $runData) {
                if (!$currentHasil) break;
                
                $val = [
                    'nilai_hasil' => $runData['nilai_hasil'] ?? null,
                    'variabel' => $runData['variabel'] ?? [],
                ];
                
                $lastResult = $this->hasilUjiService->processResult($currentHasil, $val);
                
                if ($index < $totalRuns - 1) {
                    // Fetch the clone created by processResult
                    $currentHasil = \App\Models\HasilUji::where('kegiatan_id', $currentHasil->kegiatan_id)
                        ->where('parameter_uji_id', $currentHasil->parameter_uji_id)
                        ->where('run_ke', $currentHasil->run_ke + 1)
                        ->first();
                }
            }
            
            // Redirect based on the LAST result
            if ($lastResult && $lastResult['type'] === 'error' && !str_contains($lastResult['message'], 'Tindak Lanjut')) {
                return back()->with('error', $lastResult['message']);
            }
            
            return redirect()->route('kegiatan.show', $hasilUji->kegiatan_id)->with('success', 'Semua data run berhasil disimpan secara berurutan.');
            
        } else {
            // Fallback for old single form
            $validated = $request->validate([
                'nilai_hasil' => 'nullable|numeric',
                'variabel' => 'nullable|array',
            ]);

            $result = $this->hasilUjiService->processResult($hasilUji, $validated);

            if ($result['type'] === 'warning' || $result['type'] === 'success') {
                return redirect()->route('kegiatan.show', $hasilUji->kegiatan_id)
                    ->with($result['type'], $result['message']);
            } elseif ($result['type'] === 'error') {
                if (str_contains($result['message'], 'Tindak Lanjut')) {
                    return redirect()->route('kegiatan.show', $hasilUji->kegiatan_id)
                        ->with('error', $result['message']);
                }
                return back()->with('error', $result['message']);
            }

            return redirect()->route('kegiatan.show', $hasilUji->kegiatan_id)->with('success', 'Tersimpan.');
        }
    }

    public function show($id)
    {
        $hasilUji = HasilUji::findOrFail($id);
        $this->authorize('view', $hasilUji);

        $hasilUji->load(['kegiatan', 'parameterUji', 'penginput', 'tindakLanjut.penindaklanjut']);

        return view('hasil-uji.show', compact('hasilUji'));
    }

    public function overrideWestgard(Request $request, $id)
    {
        $hasilUji = HasilUji::findOrFail($id);
        $this->authorize('update', $hasilUji); // Requires update access, which Analis and Koord have

        $request->validate([
            'override_status' => 'required|in:inlier,warning,outlier',
            'override_kode' => 'nullable|string|max:50',
        ]);

        $hasilUji->override_status = $request->input('override_status');
        $hasilUji->override_kode = $request->input('override_kode');
        $hasilUji->save();

        // If overriden to inlier, we might want to auto-close Tindak Lanjut, but keeping it simple for now
        // Let's just update the status

        return back()->with('success', 'Status Westgard berhasil di-override secara manual.');
    }

    /**
     * Menampilkan data untuk Levy-Jennings Inhouse Control Chart.
     */
    

    public function overrideEvaluasi(Request $request, $id)
    {
        $request->validate([
            'override_status' => 'required|in:Terima,Tolak',
            'keterangan_override' => 'required|string|max:500'
        ]);

        $hasilUji = HasilUji::findOrFail($id);
        $hasilUji->override_status = $request->override_status;
        $hasilUji->keterangan_override = $request->keterangan_override;
        
        // Also update the general status_berketerimaan so it reflects in other parts of the app
        if ($request->override_status === 'Terima') {
            $hasilUji->status_berketerimaan = 'inlier';
        } else {
            $hasilUji->status_berketerimaan = 'outlier';
        }

        $hasilUji->save();

        return redirect()->back()->with('success', 'Evaluasi Control Chart berhasil diperbarui (di-override).');
    }
}
