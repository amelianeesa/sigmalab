<?php

namespace App\Http\Controllers;

use App\Models\SampelInhouse;
use App\Models\SampelInhouseParameter;
use App\Models\DataHomogenitas;
use App\Models\DataStabilitas;
use App\Models\DataPenetapanTarget;
use App\Models\TabelAngkaAcak;
use App\Models\ParameterUji;
use App\Services\PemilihanSampelService;
use App\Services\PreparasiService;
use App\Services\HomogenitasService;
use App\Services\PenetapanTargetService;
use App\Services\StabilitasService;
use App\Services\RepeatabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QcInhouseController extends Controller
{
    protected $pemilihanSampelService;
    protected $preparasiService;
    protected $homogenitasService;
    protected $penetapanTargetService;
    protected $stabilitasService;
    protected $repeatabilityService;

    public function __construct(
        PemilihanSampelService $pemilihanSampelService,
        PreparasiService $preparasiService,
        HomogenitasService $homogenitasService,
        PenetapanTargetService $penetapanTargetService,
        StabilitasService $stabilitasService,
        RepeatabilityService $repeatabilityService
    ) {
        $this->pemilihanSampelService = $pemilihanSampelService;
        $this->preparasiService = $preparasiService;
        $this->homogenitasService = $homogenitasService;
        $this->penetapanTargetService = $penetapanTargetService;
        $this->stabilitasService = $stabilitasService;
        $this->repeatabilityService = $repeatabilityService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $jenis = $request->input('jenis_batubara');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = \App\Models\SampelInhouse::with(['parameters', 'pembuat'])->latest();

        $query->when($search, function($q) use ($search) {
            $q->where(function($qq) use ($search) {
                $qq->where('kode_batch', 'like', "%{$search}%")
                   ->orWhere('nama_sampel', 'like', "%{$search}%");
            });
        });

        $query->when($jenis, function($q) use ($jenis) {
            $q->where('jenis_batubara', $jenis);
        });

        $query->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        });

        $sampels = $query->paginate(10);

        return view('qc-inhouse.index', compact('sampels'));
    }

    public function create()
    {
        $parameters = ParameterUji::where('status_aktif', true)->get();
        $rentangBaku = \App\Services\PemilihanSampelService::RENTANG;
        return view('qc-inhouse.create', compact('parameters', 'rentangBaku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sampel' => 'required|string|max:150',
            'jenis_batubara' => 'required|string',
            'tm' => 'required|numeric',
            'mad' => 'required|numeric',
            'ash' => 'required|numeric',
            'vm' => 'required|numeric',
            'ts' => 'required|numeric',
            'gcv' => 'required|numeric',
        ]);

        $dataScreening = $request->only(['tm', 'mad', 'ash', 'vm', 'ts', 'gcv']);
        $jenis = $request->jenis_batubara;

        $fc = 100 - ((float)$request->mad + (float)$request->ash + (float)$request->vm);
        $dataScreening['fc'] = round($fc, 4);

        $crossErrors = $this->pemilihanSampelService->validateCrossField($dataScreening);
        if (!empty($crossErrors)) {
            return redirect()->back()->withInput()->withErrors($crossErrors);
        }

        $dbData = $this->pemilihanSampelService->convertAdbToDb($dataScreening);
        $dataScreening = array_merge($dataScreening, $dbData);

        $rangeErrors = $this->pemilihanSampelService->validateRange($jenis, $dataScreening);
        if (!empty($rangeErrors)) {
            return redirect()->back()->withInput()->withErrors($rangeErrors);
        }

        DB::beginTransaction();
        try {
            $batch = SampelInhouse::create([
                'nama_sampel' => $request->nama_sampel,
                'jenis_batubara' => $jenis,
                'data_screening' => $dataScreening,
                'tanggal_pemilihan' => now(),
                'status' => 'preparasi',
                'dibuat_oleh' => Auth::id(),
            ]);

            $namaParamGa = ['IM', 'ASH', 'VM', 'TS', 'CV'];
            $params = ParameterUji::whereIn('nama_parameter', $namaParamGa)->get();

            foreach ($params as $p) {
                SampelInhouseParameter::create([
                    'sampel_inhouse_id' => $batch->sampel_inhouse_id,
                    'parameter_uji_id' => $p->parameter_uji_id,
                    'status_parameter' => 'draft',
                ]);
            }

            DB::commit();

            return redirect()->route('qc-inhouse.preparasi', $batch->sampel_inhouse_id)
                             ->with('success', 'Tahap 1 selesai.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan sampel: ' . $e->getMessage());
        }
    }

    public function showPreparasi($id)
    {
        $batch = SampelInhouse::with('parameters.parameterUji')->findOrFail($id);


        return view('qc-inhouse.preparasi', compact('batch'));
    }

    public function storePreparasi(Request $request, $id)
    {
        $batch = SampelInhouse::findOrFail($id);

        if ($batch->status !== 'preparasi') {
            return redirect()->route('qc-inhouse.show', $id)->with('error', 'Status sampel sudah melewati tahap preparasi.');
        }

        $request->validate([
            'metode_acuan' => 'required|string',
            'jumlah_botol' => 'required|integer|min:10',
            'nomor_awal_botol' => 'required|integer|min:1',
            'kode_batch' => 'required|string|max:50',
            // 'data_equilibrium' => 'required|string', <-- (HAPUS BARIS INI)
            'catatan_preparasi' => 'nullable|string'
        ]);

        $urutanInstrumen = $this->preparasiService->generateUrutanInstrumen(10, 2);

        $batch->update([
            'metode_acuan' => $request->metode_acuan,
            'kode_batch' => $request->kode_batch,
            'jumlah_botol' => $request->jumlah_botol,
            'nomor_awal_botol' => $request->nomor_awal_botol,
            'data_equilibrium' => null,
            'bobot_konstan_tercapai' => true,
            'catatan_preparasi' => $request->catatan_preparasi,
            'tanggal_preparasi' => now(),
            'dipreparasi_oleh' => Auth::id(),
            'urutan_acak_instrumen' => $urutanInstrumen,
            'status' => 'uji_homogenitas'
        ]);

        return redirect()->route('qc-inhouse.cetak-label', $batch->sampel_inhouse_id)
                         ->with('success', 'Tahap 2 (Preparasi) selesai.');
    }

    public function cetakLabel($id)
    {
        $batch = SampelInhouse::findOrFail($id);
        $labels = $this->preparasiService->generateBatchCodes(
            $batch->kode_batch, 
            $batch->jumlah_botol, 
            $batch->nomor_awal_botol
        );

        return view('qc-inhouse.cetak-label', compact('batch', 'labels'));
    }

    public function showInstruksiHomogenitas($id)
    {
        $batch = SampelInhouse::findOrFail($id);
        $tabelAcak = TabelAngkaAcak::orderBy('urutan')->get();

        return view('qc-inhouse.instruksi-homogenitas', compact('batch', 'tabelAcak'));
    }

    public function showHomogenitas($id)
    {
        $batch = SampelInhouse::with(['parameters.parameterUji', 'parameters.dataHomogenitas'])->findOrFail($id);
        
        $tolerances = [];
        foreach ($batch->parameters as $param) {
            $tolerances[$param->id] = $this->repeatabilityService->getLimit(
                $batch->metode_acuan, 
                $param->parameterUji->nama_parameter, 
                0, 
                $batch->jenis_batubara
            );
        }

        $tabelAcak = TabelAngkaAcak::orderBy('urutan')->get()->keyBy('urutan');

        $fTabelLookup = $this->homogenitasService->getTabelF();

        $alatList = \App\Models\Alat::where('kondisi_barang', 'baik')->orderBy('nama_alat')->get();
        $personilList = \App\Models\Personil::orderBy('nama')->get();
        $barangList = \App\Models\Barang::orderBy('nama_barang')->get();

        return view('qc-inhouse.homogenitas', compact(
            'batch', 'tolerances', 'tabelAcak', 'fTabelLookup', 
            'alatList', 'personilList', 'barangList'
        ));
    }

    public function storeHomogenitas(Request $request, $id)
    {
        $batch = SampelInhouse::with('parameters')->findOrFail($id);

        if (!in_array($batch->status, ['uji_homogenitas', 'gagal_homogenitas'])) {
            return redirect()->route('qc-inhouse.show', $id)->with('error', 'Homogenitas tidak dapat diubah karena sampel sudah berada di tahap selanjutnya.');
        }
        
        DB::beginTransaction();
        try {
            $semuaHomogen = true;
            $adaData = false;

            foreach ($batch->parameters as $param) {
                $paramId = $param->id;
                $inputData = $request->input("data_{$paramId}");
                $resourceData = $request->input("resource_{$paramId}"); // Ambil data modal alat & bahan
                
                if (empty($inputData) || count($inputData) < 3) continue;
                $adaData = true;

                $oldFirstRow = DataHomogenitas::where('sampel_inhouse_parameter_id', $paramId)->where('nomor_sampel', 1)->first();
                if ($oldFirstRow && isset($oldFirstRow->data_mentah['barang_ids'])) {
                    foreach ($oldFirstRow->data_mentah['barang_ids'] as $oldBhnId) {
                        $oldQty = $oldFirstRow->data_mentah['barang_jumlah'][$oldBhnId] ?? 0;
                        if ($oldQty > 0) {
                            \App\Models\Barang::where('barang_id', $oldBhnId)->decrement('pengeluaran', $oldQty);
                        }
                    }
                }

                DataHomogenitas::where('sampel_inhouse_parameter_id', $paramId)->delete();
            
                if ($resourceData && isset($resourceData['barang_ids'])) {
                    foreach ($resourceData['barang_ids'] as $newBhnId) {
                        $newQty = $resourceData['barang_jumlah'][$newBhnId] ?? 0;
                        if ($newQty > 0) {
                            \App\Models\Barang::where('barang_id', $newBhnId)->increment('pengeluaran', $newQty);
                        }
                    }
                }

                $samplesForAnova = [];
                $isiLengkap = 0;

                foreach ($inputData as $index => $row) {
                    $nomorSampel = $index + 1;
                    
                    $d1_ada = isset($row['nilai_d1']) && $row['nilai_d1'] !== '';
                    $d2_ada = isset($row['nilai_d2']) && $row['nilai_d2'] !== '';
                    
                    if ($d1_ada && $d2_ada) {
                        $isiLengkap++;
                    }

                    $mentahToSave = $row['mentah'] ?? [];
                    if ($index === 0 && $resourceData) {
                        $mentahToSave = array_merge($mentahToSave, $resourceData);
                    }

                    $dh = DataHomogenitas::create([
                        'sampel_inhouse_parameter_id' => $paramId,
                        'nomor_sampel' => $nomorSampel,
                        'nomor_botol_fisik' => $row['nomor_botol_fisik'] ?? null,
                        'urutan_instrumen_d1' => $row['urutan_d1'] ?? null,
                        'urutan_instrumen_d2' => $row['urutan_d2'] ?? null,
                        'data_mentah' => $mentahToSave, 
                        'nilai_d1' => $d1_ada ? $row['nilai_d1'] : null,
                        'nilai_d2' => $d2_ada ? $row['nilai_d2'] : null,
                        'mean_sampel' => ($d1_ada && $d2_ada) ? ($row['nilai_d1'] + $row['nilai_d2']) / 2 : null,
                    ]);

                    if ($d1_ada && $d2_ada) {
                        $val1 = $row['nilai_d1'];
                        $val2 = $row['nilai_d2'];
                        
                        $dbVal1 = $row['nilai_db_1'] ?? null;
                        $dbVal2 = $row['nilai_db_2'] ?? null;

                        $namaParamUp = strtoupper($param->parameterUji->nama_parameter);
                        if ($namaParamUp !== 'IM' && ($dbVal1 === null || $dbVal1 === '' || $dbVal2 === null || $dbVal2 === '')) {
                            throw new \Exception("Nilai IM untuk botol {$nomorSampel} belum terisi atau belum tersimpan. Hasil basis kering (db) pada parameter {$namaParamUp} tidak bisa dihitung.");
                        }

                          if ($dbVal1 !== null && $dbVal1 !== '' && $dbVal2 !== null && $dbVal2 !== '') {
                            $val1 = $dbVal1;
                            $val2 = $dbVal2;
                        }

                        $samplesForAnova[] = [
                            'nilai_d1' => $val1,
                            'nilai_d2' => $val2
                        ];
                    }
                }

                if ($request->input('is_draft')) {
                    $param->update(['status_parameter' => 'draft']); 
                    continue;
                }

               $totalBaris = count($inputData);
               if ($isiLengkap < $totalBaris) {
                    throw new \Exception("Parameter {$param->parameterUji->nama_parameter} belum lengkap: {$isiLengkap} dari {$totalBaris} baris terisi.");
               }

               if ($totalBaris < 3) {
                    throw new \Exception("Parameter {$param->parameterUji->nama_parameter} harus memiliki minimal 3 baris data untuk uji homogenitas.");
               }

                $anova = $this->homogenitasService->calculateAnova($samplesForAnova);
                
                if (isset($anova['error'])) {
                    throw new \Exception($anova['message']);
                }

                $isHomogen = $anova['lolos'];
                if (!$isHomogen) $semuaHomogen = false;

                $param->update([
                    'mean_global' => $anova['mean_global'],
                    'sd_global' => $anova['sd_global'],
                    'f_hitung' => min($anova['f_hitung'], 999999),
                    'f_tabel' => $anova['f_tabel'],
                    'status_parameter' => $isHomogen ? 'homogen' : 'tidak_homogen',
                    'tanggal_homogenitas' => now(),
                ]);
            }

            if (!$adaData) {
                throw new \Exception("Data penimbangan tidak lengkap.");
            }

            $semuaParameterLengkap = true;
            $semuaParameterHomogen = true;
            
            $batch->refresh();
            foreach ($batch->parameters as $p) {
                if ($p->status_parameter !== 'homogen') {
                    $semuaParameterHomogen = false;
                }
                if (in_array($p->status_parameter, [null, 'pending', 'draft'])) {
                    $semuaParameterLengkap = false;
                }
            }

            if ($semuaParameterLengkap || (!$request->ajax() && $semuaHomogen)) {
                $batch->update([
                    'status' => $semuaParameterHomogen ? 'penetapan_target' : 'gagal_homogenitas',
                ]);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data parameter berhasil disimpan.'
                ]);
            }

            if ($semuaParameterHomogen) {
                return redirect()->route('qc-inhouse.penetapan-target', $id)
                                 ->with('success', 'Semua parameter HOMOGEN. Lanjut ke Penetapan Target.');
            } else {
                return redirect()->route('qc-inhouse.show', $id)
                                 ->with('error', 'Homogenitas selesai diproses. Status: ' . ($semuaParameterHomogen ? 'Homogen' : 'Tidak Homogen'));
            }

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            \Log::error('storeHomogenitas DB error', [
                'batch_id' => $id,
                'message'  => $e->getMessage(),
            ]);

            $pesan = 'Data tidak dapat disimpan karena ada nilai yang tidak wajar atau di luar batas. '
                   . 'Silakan periksa kembali data yang diinput. Jika masih gagal, hubungi admin.';

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $pesan], 422);
            }
            return redirect()->back()->withInput()->with('error', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // ============================================
    // TAHAP 4: PENETAPAN NILAI TARGET
    // ============================================
    public function showPenetapanTarget($id)
    {
        $batch = SampelInhouse::with(['parameters.parameterUji', 'parameters.dataPenetapanTarget'])->findOrFail($id);
        return view('qc-inhouse.penetapan-target', compact('batch'));
    }

    public function storePenetapanTarget(Request $request, $id)
    {
        $batch = SampelInhouse::with('parameters')->findOrFail($id);

        if ($batch->status !== 'penetapan_target' || $batch->parameters->contains(fn($p) => $p->status_parameter !== 'homogen')) {
            return redirect()->route('qc-inhouse.show', $id)->with('error', 'Penetapan target hanya bisa dilakukan jika semua parameter telah lulus uji homogenitas.');
        }
        
        DB::beginTransaction();
        try {
            foreach ($batch->parameters as $param) {
                $param->update([
                    'mean_target' => $param->mean_global,
                    'sd_target' => $param->sd_global,
                    'status_parameter' => 'target_set'
                ]);
            }

            $batch->update([
                'tanggal_penetapan_target' => now(),
                'status' => 'uji_stabilitas'
            ]);

            DB::commit();
            return redirect()->route('qc-inhouse.stabilitas', $id)
                             ->with('success', 'Nilai target berhasil disahkan dari data Uji Homogenitas. Lanjut ke Uji Stabilitas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengesahkan nilai target: ' . $e->getMessage());
        }
    }

    // ============================================
    // TAHAP 5: UJI STABILITAS
    // ============================================
    public function showStabilitas($id)
    {
        $batch = SampelInhouse::with(['parameters.parameterUji', 'parameters.dataStabilitas', 'parameters.dataHomogenitas'])->findOrFail($id);
        
        $tolerances = [];
        foreach ($batch->parameters as $param) {
            $tolerances[$param->id] = $this->repeatabilityService->getLimit(
                $batch->metode_acuan, 
                $param->parameterUji->nama_parameter, 
                0, 
                $batch->jenis_batubara
            );
        }
        $sisaBotol = [];
        if ($batch->jumlah_botol > 10) {
            $start = (int)$batch->nomor_awal_botol + 10;
            $end = (int)$batch->nomor_awal_botol + (int)$batch->jumlah_botol - 1;
            for ($i = $start; $i <= $end; $i++) {
                $sisaBotol[] = $i;
            }
        }

        $alatList = \App\Models\Alat::where('kondisi_barang', 'baik')->orderBy('nama_alat')->get();
        $personilList = \App\Models\Personil::orderBy('nama')->get();
        $barangList = \App\Models\Barang::orderBy('nama_barang')->get();

        $tTabelLookup = $this->stabilitasService->getTabelT();

        return view('qc-inhouse.stabilitas', compact(
            'batch', 'tolerances', 'sisaBotol', 
            'alatList', 'personilList', 'barangList',
            'tTabelLookup'
        ));
    }

    public function storeStabilitas(Request $request, $id)
    {
        $batch = SampelInhouse::with(['parameters.parameterUji', 'parameters.dataHomogenitas'])->findOrFail($id);

        if (!in_array($batch->status, ['uji_stabilitas', 'gagal_stabilitas'])) {
            return redirect()->route('qc-inhouse.show', $id)->with('error', 'Uji Stabilitas tidak valid untuk status sampel saat ini.');
        }
        
        DB::beginTransaction();
        try {
            $adaData = false;

            foreach ($batch->parameters as $param) {
                $paramId = $param->id;
                $inputData = $request->input("data_{$paramId}");
                $resourceData = $request->input("resource_{$paramId}");
                
                if (empty($inputData)) continue;
                $adaData = true;

                $oldFirstRow = DataStabilitas::where('sampel_inhouse_parameter_id', $paramId)->where('nomor_pengujian', 1)->first();
                if ($oldFirstRow && isset($oldFirstRow->data_mentah['barang_ids'])) {
                    foreach ($oldFirstRow->data_mentah['barang_ids'] as $oldBhnId) {
                        $oldQty = $oldFirstRow->data_mentah['barang_jumlah'][$oldBhnId] ?? 0;
                        if ($oldQty > 0) \App\Models\Barang::where('barang_id', $oldBhnId)->decrement('pengeluaran', $oldQty);
                    }
                }
                
                DataStabilitas::where('sampel_inhouse_parameter_id', $paramId)->delete();

                if ($resourceData && isset($resourceData['barang_ids'])) {
                    foreach ($resourceData['barang_ids'] as $newBhnId) {
                        $newQty = $resourceData['barang_jumlah'][$newBhnId] ?? 0;
                        if ($newQty > 0) \App\Models\Barang::where('barang_id', $newBhnId)->increment('pengeluaran', $newQty);
                    }
                }

                if ($request->input('is_draft')) {
                    $param->update(['status_parameter' => 'draft']);
                    continue; 
                }
                
                $stabilityDataY = [];
                foreach ($inputData as $index => $row) {
                    $val1 = $row['nilai_d1'] ?? null;
                    $val2 = $row['nilai_d2'] ?? null;

                    $val1 = ($val1 === '') ? null : $val1;
                    $val2 = ($val2 === '') ? null : $val2;
                    $lengkap = ($val1 !== null && $val2 !== null);

                    $meanPengujian = $lengkap ? ($val1 + $val2) / 2 : null;

                    DataStabilitas::create([
                        'sampel_inhouse_parameter_id' => $paramId,
                        'nomor_pengujian' => $index + 1,
                        'nomor_botol_fisik' => $row['nomor_botol_fisik'] ?? null,
                        'data_mentah' => array_merge(
                            $row['mentah'] ?? [],
                            [
                                'tanggal_uji' => $request->input("kondisi.tanggal"),
                                'analis' => $request->input("kondisi.analis"),
                            ],
                            ($index === 0 && $resourceData) ? [
                                'personil_ids' => $resourceData['personil_ids'] ?? [],
                                'personil_peran' => $resourceData['personil_peran'] ?? [],
                                'alat_ids' => $resourceData['alat_ids'] ?? [],
                                'barang_ids' => $resourceData['barang_ids'] ?? [],
                                'barang_jumlah' => $resourceData['barang_jumlah'] ?? [],
                            ] : []
                        ),
                        'nilai_d1' => $val1,
                        'nilai_d2' => $val2,
                        'mean_pengujian' => $meanPengujian,
                    ]);

                    if ($lengkap) {
                        $isIM = strtoupper($param->parameterUji->nama_parameter) === 'IM';
                        $db1 = $row['nilai_db_1'] ?? '';
                        $db2 = $row['nilai_db_2'] ?? '';

                        if ($isIM) {
                            $stabilityDataY[] = (float) $val1;
                            $stabilityDataY[] = (float) $val2;
                        } elseif ($db1 !== '' && $db2 !== '') {
                            $stabilityDataY[] = (float) $db1;
                            $stabilityDataY[] = (float) $db2;
                        }
                        // non-IM tanpa nilai db: dilewati, jangan campur basis
                    }
                }

                $namaParam = strtoupper($param->parameterUji->nama_parameter);
                $imParam = $batch->parameters->first(fn($p) => strtoupper($p->parameterUji->nama_parameter) === 'IM');
                $imByNo = [];
                if ($imParam) {
                    foreach ($imParam->dataHomogenitas as $h) {
                        $imByNo[$h->nomor_sampel] = ['d1' => $h->nilai_d1, 'd2' => $h->nilai_d2];
                    }
                }

                $xValues = [];
                foreach ($param->dataHomogenitas as $dh) {
                    foreach (['d1', 'd2'] as $k) {
                        $adb = $dh->{'nilai_' . $k};
                        if ($adb === null) continue;
                        $v = (float) $adb;
                        if ($namaParam !== 'IM' && isset($imByNo[$dh->nomor_sampel][$k])) {
                            $im = (float) $imByNo[$dh->nomor_sampel][$k];
                            if ($im < 100) $v = (100 / (100 - $im)) * $v;
                        }
                        $xValues[] = round($v, 2);
                    }
                }

                $nx = count($xValues);
                if ($nx < 2) throw new \Exception("Data homogenitas {$namaParam} tidak cukup.");

                $meanX = array_sum($xValues) / $nx;
                $sumSqX = 0;
                foreach ($xValues as $x) $sumSqX += pow($x - $meanX, 2);

                $targetDataset = ['n' => $nx, 'mean' => $meanX, 'sum_sq' => $sumSqX];

                if (count($stabilityDataY) < 6) {
                    $param->update(['status_parameter' => 'target_set']);
                    continue; 
                }

                $tTest = $this->stabilitasService->calculateTTest($stabilityDataY, $targetDataset);
                
                if (isset($tTest['error'])) {
                    throw new \Exception($tTest['message']);
                }

                $isStabil = $tTest['lolos'];

                $param->update([
                    'mean_stabilitas' => $tTest['mean_stabilitas'],
                    'sd_stabilitas' => $tTest['sd_stabilitas'],
                    't_hitung' => $tTest['t_hitung'],
                    't_tabel' => $tTest['t_tabel'],
                    'status_parameter' => $isStabil ? 'stabil' : 'tidak_stabil',
                    'tanggal_stabilitas' => now(),
                ]);
            }

            if (!$adaData) {
                throw new \Exception("Data pengujian stabilitas kosong.");
            }

            $allStabil = true;
            $adaGagal = false;
            foreach ($batch->parameters as $p) {
                $p->refresh();
                if ($p->status_parameter === 'tidak_stabil') {
                    $adaGagal = true;
                }
                if ($p->status_parameter !== 'stabil') {
                    $allStabil = false;
                }
            }

            if ($adaGagal) {
                $batch->update(['status' => 'gagal_stabilitas']);
            } elseif ($allStabil) {
                $batch->update(['status' => 'siap_digunakan']);
            } else {
                $batch->update(['status' => 'uji_stabilitas']);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
            }

            if ($allStabil) {
                return redirect()->route('qc-inhouse.show', $id)
                                 ->with('success', 'Uji Stabilitas Selesai! Sampel kini SIAP DIGUNAKAN. Silakan aktifkan secara manual saat ingin menjadikannya acuan harian.');
            } elseif ($adaGagal) {
                return redirect()->route('qc-inhouse.show', $id)
                                 ->with('error', 'Sampel TIDAK STABIL. Lanjutkan ke Investigasi Akar Penyebab.');
            } else {
                return redirect()->route('qc-inhouse.stabilitas', $id)
                                 ->with('success', 'Data stabilitas berhasil disimpan sementara.');
            }

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            \Log::error('storeStabilitas DB error', [
                'batch_id' => $id,
                'message'  => $e->getMessage(),
            ]);

            $pesan = 'Data tidak dapat disimpan karena ada nilai yang tidak wajar atau di luar batas. '
                   . 'Silakan periksa kembali data yang diinput. Jika masih gagal, hubungi admin.';

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $pesan], 422);
            }
            return redirect()->back()->withInput()->with('error', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $batch = SampelInhouse::with([
            'parameters.parameterUji', 
            'parameters.dataHomogenitas',
            'parameters.dataPenetapanTarget.analis',
            'parameters.dataStabilitas',
            'pembuat', 
            'preparator'
        ])->findOrFail($id);

        return view('qc-inhouse.show', compact('batch'));
    }

    public function aktifkan($id)
    {
        $batch = SampelInhouse::with('parameters.parameterUji')->findOrFail($id);

        if (!in_array($batch->status, ['siap_digunakan', 'kadaluarsa'])) {
            return back()->with('error', 'Status sampel tidak valid untuk diaktifkan.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            SampelInhouse::where('status', 'aktif')
                ->where('sampel_inhouse_id', '!=', $batch->sampel_inhouse_id)
                ->update(['status' => 'kadaluarsa']);
                
            $batch->update(['status' => 'aktif']);

            foreach ($batch->parameters as $param) {
                if ($param->status_parameter === 'stabil' && $param->parameterUji) {
                    $mean = (float) $param->mean_target;
                    $sd = (float) $param->sd_target;

                    $param->parameterUji->update([
                        'sampel_inhouse_id' => $batch->sampel_inhouse_id, 
                        'nilai_acuan' => $mean,
                        'mean' => $mean,
                        'sd' => $sd,
                        'batas_bawah' => $mean - (2 * $sd),
                        'batas_atas' => $mean + (2 * $sd),
                        'uwl_bawah' => $mean - (2 * $sd),
                        'uwl_atas' => $mean + (2 * $sd),
                        'lcl' => $mean - (3 * $sd),
                        'ucl' => $mean + (3 * $sd),
                    ]);
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('qc-inhouse.show', $id)->with('success', 'Batch berhasil diaktifkan! Master Data Parameter Uji telah disinkronkan otomatis dengan target batch ini.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal mengaktifkan batch: ' . $e->getMessage());
        }
    }

    public function nonaktifkan($id)
    {
        $batch = SampelInhouse::findOrFail($id);

        if ($batch->status !== 'aktif') {
            return back()->with('error', 'Hanya sampel yang aktif yang bisa dinonaktifkan.');
        }

        $batch->update(['status' => 'kadaluarsa']);
        
        return back()->with('success', 'Sampel berhasil dinonaktifkan.');
    }

    public function storeInvestigasi(Request $request, $id)
    {
        $request->validate([
            'akar_masalah' => 'required|string',
            'tindakan_perbaikan' => 'required|string',
        ]);

        $batch = SampelInhouse::findOrFail($id);
        $batch->update([
            'akar_masalah' => $request->akar_masalah,
            'tindakan_perbaikan' => $request->tindakan_perbaikan,
            'tanggal_investigasi' => now(),
            'diinvestigasi_oleh' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        return redirect()->route('qc-inhouse.show', $id)
                         ->with('success', 'Hasil investigasi berhasil disimpan. Silakan lakukan preparasi ulang.');
    }

    public function preparasiUlang($id)
    {
        $oldBatch = SampelInhouse::with('parameters')->findOrFail($id);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $newKodeBatch = $oldBatch->kode_batch;
            if (preg_match('/-R(\d+)$/', $newKodeBatch, $matches)) {
                $rev = intval($matches[1]) + 1;
                $newKodeBatch = preg_replace('/-R\d+$/', '-R' . $rev, $newKodeBatch);
            } else {
                $newKodeBatch .= '-R1';
            }

            $newBatch = $oldBatch->replicate([
                'jumlah_botol', 'nomor_awal_botol', 'data_pemilihan_sampel', 'data_screening', 
                'data_equilibrium', 'bobot_konstan_tercapai', 'catatan_preparasi', 
                'tanggal_preparasi', 'tanggal_penetapan_target', 'dipreparasi_oleh',
                'urutan_acak_instrumen', 'akar_masalah', 'tindakan_perbaikan',
                'tanggal_investigasi', 'diinvestigasi_oleh'
            ]);

            $newBatch->kode_batch = $newKodeBatch;
            $newBatch->status = 'preparasi'; // Kembalikan ke tahap Preparasi
            $newBatch->tanggal_pemilihan = now();
            $newBatch->dibuat_oleh = \Illuminate\Support\Facades\Auth::id();
            $newBatch->save();

            foreach ($oldBatch->parameters as $oldParam) {
                $newBatch->parameters()->create([
                    'parameter_uji_id' => $oldParam->parameter_uji_id,
                    'status_parameter' => 'draft',
                ]);
            }

            $oldBatch->update(['status' => 'arsip_gagal']);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('qc-inhouse.show', $newBatch->sampel_inhouse_id)
                             ->with('success', 'Batch berhasil direvisi menjadi ' . $newKodeBatch . '. Silakan mulai ulang preparasi sampel.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat preparasi ulang: ' . $e->getMessage());
        }
    }
}