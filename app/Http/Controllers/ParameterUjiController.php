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
        return view('parameter-uji.create');
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

    public function show(ParameterUji $parameterUji)
    {
        $this->authorize('view', $parameterUji);
        return view('parameter-uji.show', compact('parameterUji'));
    }

    public function edit(ParameterUji $parameterUji)
    {
        $this->authorize('update', $parameterUji);
        return view('parameter-uji.edit', compact('parameterUji'));
    }

    public function update(\App\Http\Requests\ParameterUjiRequest $request, ParameterUji $parameterUji)
    {
        $this->authorize('update', $parameterUji);

        $validated = $request->validated();
        $validated['langkah_kalkulasi'] = $request->input('langkah_kalkulasi'); // already merged

        $parameterUji->update($validated);

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
    
    public function calculateHistoricalStats(ParameterUji $parameterUji)
    {
        $this->authorize('view', $parameterUji);

        $validList = HasilUji::where('parameter_uji_id', $parameterUji->parameter_uji_id)
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
