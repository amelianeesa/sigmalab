<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CrmKatalog;
use App\Models\CrmSertifikat;
use App\Models\ParameterUji;

class CrmKatalogController extends Controller
{
    public function index()
    {
        $katalogList = CrmKatalog::withCount('sertifikats')->orderBy('created_at', 'desc')->get();
        return view('qc-crm.katalog.index', compact('katalogList'));
    }

    public function create()
    {
        $parameterList = ParameterUji::where('status_aktif', true)->orderBy('nama_parameter')->get();
        return view('qc-crm.katalog.create', compact('parameterList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:150',
            'produsen' => 'nullable|string|max:150',
            'nomor_lot' => 'required|string|max:50|unique:crm_katalog,nomor_lot',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'tanggal_expired' => 'nullable|date',
            'parameters' => 'nullable|array',
            'parameters.*.parameter_uji_id' => 'required|exists:parameter_uji,parameter_uji_id',
            'parameters.*.cert_value' => 'required|numeric',
            'parameters.*.cert_u' => 'required|numeric',
        ]);

        $katalog = CrmKatalog::create([
            'nama_produk' => $request->nama_produk,
            'produsen' => $request->produsen,
            'nomor_lot' => $request->nomor_lot,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'tanggal_expired' => $request->tanggal_expired,
            'is_active' => true,
        ]);

        if (!empty($request->parameters)) {
            $sertifikats = [];
            foreach ($request->parameters as $p) {
                $sertifikats[] = [
                    'crm_katalog_id' => $katalog->id,
                    'parameter_uji_id' => $p['parameter_uji_id'],
                    'cert_value' => $p['cert_value'],
                    'cert_u' => $p['cert_u'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            CrmSertifikat::insert($sertifikats);
        }

        return redirect()->route('crm-katalog.index')->with('success', 'Botol CRM beserta nilai acuannya berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $katalog = CrmKatalog::findOrFail($id);
        $request->validate([
            'nama_produk' => 'required|string|max:150',
            'produsen' => 'nullable|string|max:150',
            'nomor_lot' => 'required|string|max:50|unique:crm_katalog,nomor_lot,'.$id,
            'nomor_sertifikat' => 'nullable|string|max:100',
            'tanggal_expired' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        $katalog->update([
            'nama_produk' => $request->nama_produk,
            'produsen' => $request->produsen,
            'nomor_lot' => $request->nomor_lot,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'tanggal_expired' => $request->tanggal_expired,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('crm-katalog.index')->with('success', 'Botol CRM berhasil diperbarui.');
    }

    public function show($id)
    {
        $katalog = CrmKatalog::with('sertifikats.parameterUji')->findOrFail($id);
        $parameterList = ParameterUji::where('status_aktif', true)->orderBy('nama_parameter')->get();
        
        return view('qc-crm.katalog.show', compact('katalog', 'parameterList'));
    }

    public function storeSertifikat(Request $request, $id)
    {
        $katalog = CrmKatalog::findOrFail($id);
        $request->validate([
            'parameter_uji_id' => 'required|exists:parameter_uji,parameter_uji_id',
            'cert_value' => 'required|numeric',
            'cert_u' => 'required|numeric',
        ]);

        // Cek apakah parameter sudah ada di sertifikat ini
        if ($katalog->sertifikats()->where('parameter_uji_id', $request->parameter_uji_id)->exists()) {
            return redirect()->back()->with('error', 'Parameter uji tersebut sudah ada di sertifikat botol ini. Silakan hapus yang lama terlebih dahulu jika ingin mengganti.');
        }

        CrmSertifikat::create([
            'crm_katalog_id' => $katalog->id,
            'parameter_uji_id' => $request->parameter_uji_id,
            'cert_value' => $request->cert_value,
            'cert_u' => $request->cert_u,
        ]);

        return redirect()->route('crm-katalog.show', $katalog->id)->with('success', 'Nilai sertifikat parameter berhasil ditambahkan.');
    }

    public function destroySertifikat($id, $sertifikat_id)
    {
        $sertifikat = CrmSertifikat::where('crm_katalog_id', $id)->findOrFail($sertifikat_id);
        $sertifikat->delete();

        return redirect()->route('crm-katalog.show', $id)->with('success', 'Nilai sertifikat parameter berhasil dihapus.');
    }
}
