<?php

namespace App\Http\Controllers;

use App\Models\ParameterUji;
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

    public function store(Request $request)
    {
        $this->authorize('create', ParameterUji::class);

        $validated = $request->validate([
            'nama_parameter' => 'required|string|max:50',
            'satuan' => 'required|string|max:20',
            'nilai_acuan' => 'required|numeric',
            'batas_bawah' => 'required|numeric',
            'batas_atas' => 'required|numeric',
            'metode_kriteria' => 'nullable|string|max:50',
            'rumus_kalkulasi' => 'nullable|string',
        ]);

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

    public function update(Request $request, ParameterUji $parameterUji)
    {
        $this->authorize('update', $parameterUji);

        $validated = $request->validate([
            'nama_parameter' => 'required|string|max:50',
            'satuan' => 'required|string|max:20',
            'nilai_acuan' => 'required|numeric',
            'batas_bawah' => 'required|numeric',
            'batas_atas' => 'required|numeric',
            'metode_kriteria' => 'nullable|string|max:50',
            'rumus_kalkulasi' => 'nullable|string',
            'status_aktif' => 'boolean',
        ]);

        $parameterUji->update($validated);

        return redirect()->route('parameter-uji.index')->with('success', 'Parameter uji berhasil diperbarui.');
    }

    public function destroy(ParameterUji $parameterUji)
    {
        $this->authorize('delete', $parameterUji);

        if ($parameterUji->sudahDipakaiDiHasilUji()) {
            // Soft deactivate
            $parameterUji->update(['status_aktif' => false]);
            return redirect()->route('parameter-uji.index')->with('success', 'Parameter uji dinonaktifkan karena sudah pernah digunakan dalam hasil uji.');
        } else {
            // Hard delete
            $parameterUji->delete();
            return redirect()->route('parameter-uji.index')->with('success', 'Parameter uji berhasil dihapus permanen.');
        }
    }
}
