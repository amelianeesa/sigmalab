<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QcCrm;
use App\Models\CrmKatalog;
use App\Models\CrmSertifikat;
use App\Models\Personil;
use App\Models\Alat;
use App\Models\Barang;

use Illuminate\Support\Facades\DB;

class QcCrmController extends Controller
{
    public function index()
    {
        $kegiatanList = QcCrm::with(['crmKatalog', 'parameterUji', 'analis'])
            ->orderBy('tanggal_uji', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $katalogs = CrmKatalog::with(['sertifikats.parameterUji', 'verifikasiTeknis.parameterUji', 'verifikasiTeknis.analis'])->orderBy('created_at', 'desc')->get();
        
        return view('qc-crm.index', compact('kegiatanList', 'katalogs'));
    }

    public function create()
    {
        $activeCrms = CrmKatalog::where('is_active', true)->orderBy('nomor_lot')->get();
        $personilList = Personil::orderBy('nama')->get();
        $alatList = Alat::where('kondisi_barang', 'baik')->orderBy('nama_alat')->get();
        $barangList = Barang::orderBy('nama_barang')->get();
        
        $allParameters = \App\Models\ParameterUji::where('status_aktif', true)->orderBy('nama_parameter')->get();

        return view('qc-crm.create', compact('activeCrms', 'personilList', 'alatList', 'barangList', 'allParameters'));
    }

    public function apiGetParameters($katalog_id)
    {
        $sertifikats = CrmSertifikat::with('parameterUji')
            ->where('crm_katalog_id', $katalog_id)
            ->get();
            
        return response()->json($sertifikats);
    }

    public function store(Request $request)
    {
        // Similar handling to In-House, but we only save to qc_crms
        $request->validate([
            'crm_katalog_id' => 'required|exists:crm_katalog,id',
            'tanggal_uji' => 'required|date',
            'analis_id' => 'nullable|exists:personil,id',
            'params' => 'required|array', // The parameters checked and their data
        ]);

        $katalogId = $request->crm_katalog_id;
        $tanggalUji = $request->tanggal_uji;
        $analisId = $request->analis_id;
        
        $katalog = CrmKatalog::with('sertifikats.parameterUji')->findOrFail($katalogId);
        $sertifikatMap = $katalog->sertifikats->keyBy('parameter_uji_id');

        DB::beginTransaction();
        try {
            foreach ($request->params as $paramId => $data) {
                // Check if user selected this param
                if (!isset($data['selected'])) continue;
                
                // Check if this param is certified for this bottle
                if (!isset($sertifikatMap[$paramId])) continue;
                $sertifikat = $sertifikatMap[$paramId];
                
                $nilai_d1 = isset($data['d1']) ? floatval($data['d1']) : null;
                $nilai_d2 = isset($data['d2']) ? floatval($data['d2']) : null;
                $nilai_db_1 = isset($data['db1']) ? floatval($data['db1']) : null;
                $nilai_db_2 = isset($data['db2']) ? floatval($data['db2']) : null;
                
                // Evaluasi mutlak, nilai_akhir prioritaskan rata-rata DB jika ada
                if ($nilai_db_1 !== null && $nilai_db_2 !== null) {
                    $nilai_akhir = ($nilai_db_1 + $nilai_db_2) / 2;
                } else if ($nilai_d1 !== null && $nilai_d2 !== null) {
                    $nilai_akhir = ($nilai_d1 + $nilai_d2) / 2;
                } else {
                    $nilai_akhir = 0; // fallback
                }
                
                $batas_bawah = $sertifikat->cert_value - $sertifikat->cert_u;
                $batas_atas = $sertifikat->cert_value + $sertifikat->cert_u;
                
                $status = ($nilai_akhir >= $batas_bawah && $nilai_akhir <= $batas_atas) ? 'inlier' : 'outlier';
                
                QcCrm::create([
                    'crm_katalog_id' => $katalogId,
                    'parameter_uji_id' => $paramId,
                    'tanggal_uji' => $tanggalUji,
                    'analis_id' => $analisId,
                    'nilai_d1' => $nilai_d1,
                    'nilai_d2' => $nilai_d2,
                    'nilai_db_1' => $nilai_db_1,
                    'nilai_db_2' => $nilai_db_2,
                    'nilai_akhir' => $nilai_akhir,
                    'cert_value' => $sertifikat->cert_value,
                    'cert_u' => $sertifikat->cert_u,
                    'status_evaluasi' => $status,
                    'data_mentah' => isset($data['mentah']) ? $data['mentah'] : null,
                    'catatan' => $data['catatan'] ?? null,
                ]);
            }
            DB::commit();
            return redirect()->route('qc-crm.index')->with('success', 'Data pengujian CRM berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}


