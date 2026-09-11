<?php

namespace App\Http\Controllers;

use App\Models\ParameterUji;
use App\Models\HasilUji;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ParameterUjiController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', ParameterUji::class);

        $filterStatus = $request->input('filter_status', 'semua');
        $search = $request->input('search');

        $query = ParameterUji::query();

        if ($search) {
            $query->where('nama_parameter', 'LIKE', "%{$search}%");
        }

        if ($filterStatus === 'aktif') {
            $query->where('status_aktif', true);
        } elseif ($filterStatus === 'nonaktif') {
            $query->where('status_aktif', false);
        }

        $parameterUji = $query->latest()->paginate(10);

        return view('parameter-uji.index', compact('parameterUji', 'filterStatus', 'search'));
    }

    public function create()
    {
        $this->authorize('create', ParameterUji::class);
        $katalogCrmList = \App\Models\CrmKatalog::where('is_active', true)->get();
        return view('parameter-uji.create', compact('katalogCrmList'));
    }

    public function store(\App\Http\Requests\ParameterUjiRequest $request)
    {
        $this->authorize('create', ParameterUji::class);

        $validated = $request->validated();
        $validated['langkah_kalkulasi'] = $request->input('langkah_kalkulasi'); // already merged
        $validated['status_aktif'] = true;

        ParameterUji::create($validated);

        return redirect()->route('parameter-uji.index')->with('success', 'Parameter uji berhasil ditambahkan.');
    }

    public function cetakControlChart(\Illuminate\Http\Request $request, ParameterUji $parameterUji)
    {
        $this->authorize('view', $parameterUji);
        $chartService = app(\App\Services\ChartDataService::class);
        $data = $chartService->prepareInhouseControlData($request, true, $parameterUji);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($data['pdfView'], [
            'parameterList' => [],
            'selectedParameter' => $parameterUji,
            'chartData' => $data['chartData'],
            'hasilList' => $data['hasilList'],
            'stats' => $data['stats']
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Control_Chart_' . $parameterUji->nama_parameter . '.pdf');
    }

    public function show(\Illuminate\Http\Request $request, ParameterUji $parameterUji)
    {
        $this->authorize('view', $parameterUji);
        
        $chartService = app(\App\Services\ChartDataService::class);
        $data = $chartService->prepareInhouseControlData($request, false, $parameterUji);
        
        $tab = $request->input('tab', 'in_house');
        // Fallback backward compatibility, 'jenis_grafik' was used by old inhouse control
        if ($request->has('jenis_grafik')) {
            $tab = $request->input('jenis_grafik');
        }

        $limitDate = $parameterUji->limit_terakhir_dihitung;
        $newPointsQuery = \App\Models\HasilUji::where('parameter_uji_id', $parameterUji->parameter_uji_id)
            ->where('jenis_kontrol', 'in_house')
            ->whereNotNull('nilai_hasil')
            ->whereIn('status_berketerimaan', ['inlier', 'diterima']);
        
        if ($limitDate) {
            $newPointsQuery->where('created_at', '>', $limitDate);
        }
        
        $newPointsCount = $newPointsQuery->count();

        return view('parameter-uji.show', [
            'parameterUji' => $parameterUji,
            'newPointsCount' => $newPointsCount,
            'tab' => $tab,
            'parameterList' => \App\Models\ParameterUji::where('status_aktif', true)->orderBy('nama_parameter')->get(),
            'chartData' => $data['chartData'],
            'hasilList' => $data['hasilList'],
            'stats' => $data['stats'],
            'partialView' => $data['viewName'] ?? 'parameter-uji.partials.im',
            'jenisGrafik' => $tab
        ]);
    }

    public function edit(ParameterUji $parameterUji)
    {
        $this->authorize('update', $parameterUji);
        $katalogCrmList = \App\Models\CrmKatalog::where('is_active', true)->get();
        return view('parameter-uji.edit', compact('parameterUji', 'katalogCrmList'));
    }

    public function update(\App\Http\Requests\ParameterUjiRequest $request, ParameterUji $parameterUji)
    {
        $this->authorize('update', $parameterUji);

        $validated = $request->validated();
        $validated['langkah_kalkulasi'] = $request->input('langkah_kalkulasi'); // already merged

        $parameterUji->update($validated);

        if ($request->has('crm_sertifikat')) {
            foreach ($request->input('crm_sertifikat') as $katalogId => $values) {
                if (!empty($values['cert_value'])) {
                    \App\Models\CrmSertifikat::updateOrCreate(
                        ['crm_katalog_id' => $katalogId, 'parameter_uji_id' => $parameterUji->parameter_uji_id],
                        ['cert_value' => $values['cert_value'], 'cert_u' => $values['cert_u'] ?? null]
                    );
                }
            }
        }

        return redirect()->route('parameter-uji.index')->with('success', 'Parameter uji berhasil diperbarui.');
    }

    public function destroy(ParameterUji $parameterUji)
    {
        $this->authorize('delete', $parameterUji);

        if ($parameterUji->sudahDipakaiDiHasilUji()) {
            $parameterUji->update(['status_aktif' => false]);
            return redirect()->route('parameter-uji.index')->with('success', 'Parameter uji dinonaktifkan karena sudah pernah digunakan dalam hasil uji.');
        } else {
            $parameterUji->delete();
            return redirect()->route('parameter-uji.index')->with('success', 'Parameter uji berhasil dihapus permanen.');
        }
    }
    
        /**
     * AJAX endpoint to store a new CRM Katalog globally from the Parameter Uji modal
     */
    public function storeCrmKatalog(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:150',
            'produsen' => 'nullable|string|max:150',
            'nomor_lot' => 'required|string|max:50|unique:crm_katalog,nomor_lot',
            'tanggal_expired' => 'nullable|date',
            'cert_value' => 'nullable|numeric',
            'cert_u' => 'nullable|numeric',
            'parameter_uji_id' => 'required|exists:parameter_uji,parameter_uji_id'
        ]);

        try {
            $katalog = \App\Models\CrmKatalog::create([
                'nama_produk' => $request->nama_produk,
                'produsen' => $request->produsen,
                'nomor_lot' => $request->nomor_lot,
                'tanggal_expired' => $request->tanggal_expired,
                'is_active' => true,
            ]);

            if ($request->filled('cert_value')) {
                \App\Models\CrmSertifikat::create([
                    'crm_katalog_id' => $katalog->id ?? $katalog->crm_katalog_id,
                    'parameter_uji_id' => $request->parameter_uji_id,
                    'cert_value' => $request->cert_value,
                    'cert_u' => $request->cert_u ?? 0,
                ]);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'data' => $katalog]);
            }
            return redirect()->back()->with('success', 'Katalog CRM berhasil ditambahkan.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * AJAX endpoint to delete a CRM Katalog
     */
    public function destroyCrmKatalog($id)
    {
        try {
            $katalog = \App\Models\CrmKatalog::findOrFail($id);
            $katalog->delete(); // this will cascade to crm_sertifikat because of foreign key constraint
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->back()->with('success', 'Botol CRM berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function calculateHistoricalStats(ParameterUji $parameterUji)
    {
        $this->authorize('view', $parameterUji);

        $validList = HasilUji::where('parameter_uji_id', $parameterUji->parameter_uji_id)
            ->where('jenis_kontrol', 'in_house')
            ->whereNotNull('nilai_hasil')
            ->where('status_berketerimaan', '!=', 'gagal_duplo')
            ->where('status_berketerimaan', '!=', 'outlier')
            ->get();

        $values = $validList->pluck('nilai_hasil')
            ->filter(fn($v) => is_numeric($v))
            ->map(fn($v) => (float)$v)
            ->toArray();
        
        if (count($values) > 1) {
            $mean = array_sum($values) / count($values);
            $variance = 0.0;
            foreach ($values as $val) {
                $variance += pow($val - $mean, 2);
            }
            $variance /= (count($values) - 1);
            $sd = sqrt($variance);
        } else {
            $mean = count($values) == 1 ? $values[0] : 0;
            $sd = 0;
        }

        return response()->json([
            'mean' => round($mean, 4),
            'sd' => round($sd, 4),
            'count' => count($values)
        ]);
    }    public function controlChart($id)
    {
        $parameterUji = ParameterUji::findOrFail($id);
        $this->authorize('view', $parameterUji);

        // Pastikan mean dan sd sudah ada
        if (empty($parameterUji->mean) || empty($parameterUji->sd)) {
            return redirect()->route('parameter-uji.edit', $id)
                ->with('error', 'Mean dan SD belum dihitung/dikonfigurasi untuk parameter ini. Silakan hitung dari data histori terlebih dahulu.');
        }

        // Ambil data hasil uji terkait parameter ini, yang sudah selesai/diterima/inlier (sesuai filter yang mungkin dibutuhkan)
        // Kita ambil semua hasil_uji yang ada nilai_hasil nya, urutkan berdasarkan waktu
        $hasilUjiList = \App\Models\HasilUji::where('parameter_uji_id', $id)
            ->whereNotNull('nilai_hasil')
            ->orderBy('created_at', 'asc')
            ->get();

        // Siapkan data untuk view
        // Nilai µ-1σ dan µ+1σ dihitung secara on the fly (sementara lcl, lwl, uwl, ucl sudah ada di db)
        $mean = (float) $parameterUji->mean;
        $sd = (float) $parameterUji->sd;
        $minus1Sd = $mean - $sd;
        $plus1Sd = $mean + $sd;

        return view('parameter-uji.control-chart', compact('parameterUji', 'hasilUjiList', 'minus1Sd', 'plus1Sd'));
    }
}
