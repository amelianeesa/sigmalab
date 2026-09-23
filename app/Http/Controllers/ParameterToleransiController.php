<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParameterToleransi;
use App\Models\ParameterUji;

class ParameterToleransiController extends Controller
{
    public function index()
    {
        // Mengambil semua parameter beserta data toleransinya
        $parameters = ParameterUji::with('toleransis')->orderBy('nama_parameter', 'asc')->get();
        
        return view('qc-uji-banding.master_toleransi', compact('parameters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parameter_uji_id' => 'required|exists:parameter_uji,parameter_uji_id',
            'kategori_label'   => 'nullable|string',
            'metode'           => 'nullable|string', // <-- INI YANG BARU
            'sub_parameter'    => 'nullable|string', // <-- INI YANG BARU
            'range_label'      => 'nullable|string',
            'range_min'        => 'nullable|numeric',
            'range_max'        => 'nullable|numeric',
            'formula_r'        => 'required|string',
            'formula_R_besar'  => 'required|string',
            'catatan'          => 'nullable|string', // <-- INI YANG BARU
        ]);

        ParameterToleransi::create($validated);
        return redirect()->back()->with([
            'success' => 'Batas toleransi berhasil ditambahkan!',
            'active_tab' => $request->parameter_uji_id 
        ]);
    }

    public function update(Request $request, $id)
    {
        $toleransi = ParameterToleransi::findOrFail($id);
        
        $validated = $request->validate([
            'kategori_label'   => 'nullable|string',
            'metode'           => 'nullable|string',
            'sub_parameter'    => 'nullable|string',
            'range_label'      => 'nullable|string',
            'range_min'        => 'nullable|numeric',
            'range_max'        => 'nullable|numeric',
            'formula_r'        => 'required|string',
            'formula_R_besar'  => 'required|string',
            'catatan'          => 'nullable|string',
        ]);

        $toleransi->update($validated);
        
        return redirect()->back()->with([
            'success' => 'Batas toleransi berhasil diperbarui!',
            'active_tab' => $toleransi->parameter_uji_id
        ]);
    }

    public function destroy($id)
    {
        $toleransi = ParameterToleransi::findOrFail($id);
        
        // Simpan ID parameter sebelum datanya dihapus
        $parameter_uji_id = $toleransi->parameter_uji_id; 
        
        $toleransi->delete();
        
        return redirect()->back()->with([
            'success' => 'Batas toleransi berhasil dihapus!',
            'active_tab' => $parameter_uji_id // Menahan tab agar tidak melompat
        ]);
    }
}