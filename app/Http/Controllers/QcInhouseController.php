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

        $query = \App\Models\SampelInhouse::with(['parameters', 'pembuat'])
            ->orderByRaw("CASE WHEN status = 'aktif' THEN 1 ELSE 2 END")
            ->latest();

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
        ParameterUji::firstOrCreate(
            ['nama_parameter' => 'AFT'],
            [
                'satuan' => '°C',
                'kategori_parameter' => 'Ash Behaviour & Physical',
                'nilai_acuan' => 1200,
                'batas_bawah' => 0,
                'batas_atas' => 2000,
                'status_aktif' => true,
            ]
        );

        ParameterUji::firstOrCreate(
            ['nama_parameter' => 'CHN'],
            [
                'satuan' => '%',
                'kategori_parameter' => 'Ultimate Analysis',
                'nilai_acuan' => 50,
                'batas_bawah' => 0,
                'batas_atas' => 100,
                'status_aktif' => true,
            ]
        );

        $targetParamNames = ['IM', 'ASH', 'VM', 'TS', 'CV', 'AFT', 'CHN'];
        $parameters = ParameterUji::whereIn('nama_parameter', $targetParamNames)
            ->where('status_aktif', true)
            ->orderByRaw("FIELD(nama_parameter, 'IM', 'ASH', 'VM', 'TS', 'CV', 'AFT', 'CHN')")
            ->get();

        $rentangBaku = \App\Services\PemilihanSampelService::RENTANG;
        return view('qc-inhouse.create', compact('parameters', 'rentangBaku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sampel' => 'required|string|max:150',
            'jenis_batubara' => 'nullable|string',
            'selected_params' => 'required|array|min:1',
            'tm' => 'nullable|numeric',
            'mad' => 'nullable|numeric',
            'ash' => 'nullable|numeric',
            'vm' => 'nullable|numeric',
            'ts' => 'nullable|numeric',
            'gcv' => 'nullable|numeric',
        ], [
            'selected_params.required' => 'Pilih minimal satu parameter uji untuk batch ini.',
            'selected_params.min' => 'Pilih minimal satu parameter uji untuk batch ini.',
        ]);

        $dataScreening = array_filter(
            $request->only(['tm', 'mad', 'ash', 'vm', 'ts', 'gcv']),
            fn($val) => $val !== null && $val !== ''
        );
        $jenis = $request->jenis_batubara;

        if (isset($dataScreening['mad'], $dataScreening['ash'], $dataScreening['vm'])) {
            $fc = 100 - ((float)$dataScreening['mad'] + (float)$dataScreening['ash'] + (float)$dataScreening['vm']);
            $dataScreening['fc'] = round($fc, 4);
        } else {
            $dataScreening['fc'] = null;
        }

        $crossErrors = $this->pemilihanSampelService->validateCrossField($dataScreening);
        if (!empty($crossErrors)) {
            return redirect()->back()->withInput()->withErrors($crossErrors);
        }

        $dbData = $this->pemilihanSampelService->convertAdbToDb($dataScreening);
        $dataScreening = array_merge($dataScreening, $dbData);

        if (!empty($jenis) && !empty($dataScreening)) {
            $rangeErrors = $this->pemilihanSampelService->validateRange($jenis, $dataScreening);
            if (!empty($rangeErrors)) {
                return redirect()->back()->withInput()->withErrors($rangeErrors);
            }
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

            $selectedParamIds = $request->input('selected_params', []);
            $params = ParameterUji::whereIn('parameter_uji_id', $selectedParamIds)
                ->orderByRaw("FIELD(nama_parameter, 'IM', 'ASH', 'VM', 'TS', 'CV', 'C', 'H', 'N', 'AFT-IDT', 'AFT-ST', 'AFT-HT', 'AFT-FT', 'HGI')")
                ->get();

            if ($params->isEmpty()) {
                $params = ParameterUji::whereIn('nama_parameter', ['IM', 'ASH', 'VM', 'TS', 'CV'])
                    ->orderByRaw("FIELD(nama_parameter, 'IM', 'ASH', 'VM', 'TS', 'CV')")
                    ->get();
            }

            foreach ($params as $p) {
                SampelInhouseParameter::create([
                    'sampel_inhouse_id' => $batch->sampel_inhouse_id,
                    'parameter_uji_id' => $p->parameter_uji_id,
                    'status_parameter' => 'draft',
                ]);
            }

             activity()
                ->performedOn($batch)
                ->causedBy(Auth::user())
                ->event('created')
                ->log("Membuat batch sampel in-house baru: {$batch->kode_batch} ({$batch->nama_sampel})");

            DB::commit();

            return redirect()->route('qc-inhouse.preparasi', $batch->sampel_inhouse_id)
                             ->with('success', 'Tahap 1 selesai.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan sampel: ' . $e->getMessage());
        }
    }

    public function showPemilihan($id)
    {
        $batch = SampelInhouse::with(['parameters.parameterUji', 'pembuat'])->findOrFail($id);
        $screening = $batch->data_screening ?? [];

        return view('qc-inhouse.pemilihan', compact('batch', 'screening'));
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

        $this->sortBatchParameters($batch);
        
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

        // Cek apakah batch ini memiliki parameter IM sendiri
        $batchHasIm = $batch->parameters->contains(function ($p) {
            return strtoupper($p->parameterUji->nama_parameter ?? '') === 'IM';
        });

        // Cek apakah batch membutuhkan data IM (memiliki ASH, VM, TS, atau CV)
        $needsIm = $batch->parameters->contains(function ($p) {
            return in_array(strtoupper($p->parameterUji->nama_parameter ?? ''), ['ASH', 'VM', 'TS', 'CV']);
        });

        $availableImBatches = [];
        $savedImReference = null;

        if (!$batchHasIm && $needsIm) {
            $savedImReference = $batch->data_pemilihan_sampel['referensi_im'] ?? null;

            // Ambil batch lain yang memiliki data homogenitas parameter IM
            $availableImBatches = SampelInhouse::where('sampel_inhouse_id', '!=', $id)
                ->whereHas('parameters', function ($q) {
                    $q->whereHas('parameterUji', fn($qu) => $qu->where('nama_parameter', 'IM'))
                      ->whereHas('dataHomogenitas');
                })
                ->with(['parameters' => function ($q) {
                    $q->whereHas('parameterUji', fn($qu) => $qu->where('nama_parameter', 'IM'))
                      ->with('dataHomogenitas');
                }])
                ->orderBy('created_at', 'desc')
                ->get()
                                ->map(function ($b) {
                    $imParam = $b->parameters->first(fn($p) => strtoupper($p->parameterUji->nama_parameter ?? '') === 'IM');
                    $imData = $imParam ? $imParam->dataHomogenitas->sortBy('nomor_sampel')->values() : collect();
                    return [
                        'id' => $b->sampel_inhouse_id,
                        'kode_batch' => $b->kode_batch ?? ('Batch #' . $b->sampel_inhouse_id),
                        'nama_sampel' => $b->nama_sampel,
                        'tanggal' => $b->created_at ? $b->created_at->format('d/m/Y') : '-',
                        'mean_global' => $imParam->mean_global !== null ? (float) $imParam->mean_global : null,
                        'sd_global' => $imParam->sd_global !== null ? (float) $imParam->sd_global : null,
                        'status_parameter' => $imParam->status_parameter ?? '-',
                        'data' => $imData->map(function ($dh) {
                            $mentah = $dh->data_mentah ?? [];
                            return [
                                'nomor_sampel' => $dh->nomor_sampel,
                                'nomor_botol_fisik' => $dh->nomor_botol_fisik ?? $dh->nomor_sampel,
                                'simplo_adb' => $dh->nilai_d1 !== null ? (float) $dh->nilai_d1 : null,
                                'duplo_adb' => $dh->nilai_d2 !== null ? (float) $dh->nilai_d2 : null,
                                'avg_adb' => $dh->mean_sampel !== null ? (float) $dh->mean_sampel : null,
                                // Data mentah penimbangan M1, M2, M3, A, B
                                'dish_1' => $mentah['dish_1'] ?? '',
                                'm1_1'   => $mentah['m1_1'] ?? '',
                                'm2_1'   => $mentah['m2_1'] ?? '',
                                'm3_1'   => $mentah['m3_1'] ?? '',
                                'a_1'    => $mentah['a_1'] ?? '',
                                'b_1'    => $mentah['b_1'] ?? '',
                                'dish_2' => $mentah['dish_2'] ?? '',
                                'm1_2'   => $mentah['m1_2'] ?? '',
                                'm2_2'   => $mentah['m2_2'] ?? '',
                                'm3_2'   => $mentah['m3_2'] ?? '',
                                'a_2'    => $mentah['a_2'] ?? '',
                                'b_2'    => $mentah['b_2'] ?? '',
                            ];
                        })->all()
                    ];
                })->values();
        }

        return view('qc-inhouse.homogenitas', compact(
            'batch', 'tolerances', 'tabelAcak', 'fTabelLookup', 
            'alatList', 'personilList', 'barangList',
            'batchHasIm', 'needsIm', 'availableImBatches', 'savedImReference'
        ));
    }

    public function storeHomogenitas(Request $request, $id)
    {
        $batch = SampelInhouse::with('parameters.parameterUji')->findOrFail($id);

        if (!in_array($batch->status, ['uji_homogenitas', 'gagal_homogenitas'])) {
            return redirect()->route('qc-inhouse.show', $id)->with('error', 'Homogenitas tidak dapat diubah karena sampel sudah berada di tahap selanjutnya.');
        }
        
        DB::beginTransaction();
        try {
            if ($request->filled('referensi_im_mode')) {
                $pemilihanData = $batch->data_pemilihan_sampel ?? [];
                $pemilihanData['referensi_im'] = [
                    'mode' => $request->input('referensi_im_mode'),
                    'batch_id' => $request->input('referensi_im_batch_id'),
                    'manual_val' => $request->input('referensi_im_manual'),
                    'data' => json_decode($request->input('referensi_im_data', '[]'), true)
                ];
                $batch->update(['data_pemilihan_sampel' => $pemilihanData]);
            }

            $semuaHomogen = true;
            $adaData = false;

            $oldBatchStatus = $batch->status;
            $diffChanges = []; 

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
                $namaParam = $param->parameterUji->nama_parameter ?? 'Param';

                $oldDataRows = DataHomogenitas::where('sampel_inhouse_parameter_id', $paramId)
                    ->get()
                    ->keyBy('nomor_sampel');
                $oldParamStatus = $param->status_parameter;
                $oldFHitung = $param->f_hitung;

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

                    if (isset($row['nilai_db_1']) && $row['nilai_db_1'] !== '') $mentahToSave['nilai_db_1'] = $row['nilai_db_1'];
                    if (isset($row['nilai_db_2']) && $row['nilai_db_2'] !== '') $mentahToSave['nilai_db_2'] = $row['nilai_db_2'];

                    $valUtama1 = null;
                    $valUtama2 = null;
                    if (isset($mentahToSave['st_1']) && $mentahToSave['st_1'] !== '') {
                        $valUtama1 = floatval($mentahToSave['st_1']);
                    } elseif (isset($mentahToSave['st']) && $mentahToSave['st'] !== '') {
                        $valUtama1 = floatval($mentahToSave['st']);
                    }
                    if (isset($mentahToSave['st_2']) && $mentahToSave['st_2'] !== '') {
                        $valUtama2 = floatval($mentahToSave['st_2']);
                    }

                    if (isset($mentahToSave['c_1']) && $mentahToSave['c_1'] !== '') {
                        $valUtama1 = floatval($mentahToSave['c_1']);
                    } elseif (isset($mentahToSave['c']) && $mentahToSave['c'] !== '') {
                        $valUtama1 = floatval($mentahToSave['c']);
                    }
                    if (isset($mentahToSave['c_2']) && $mentahToSave['c_2'] !== '') {
                        $valUtama2 = floatval($mentahToSave['c_2']);
                    }

                    $finalD1 = $valUtama1 !== null ? $valUtama1 : ($d1_ada ? floatval($row['nilai_d1']) : null);
                    $finalD2 = $valUtama2 !== null ? $valUtama2 : ($d2_ada ? floatval($row['nilai_d2']) : null);
                    $meanSampel = ($finalD1 !== null && $finalD2 !== null) 
                        ? ($finalD1 + $finalD2) / 2 
                        : ($finalD1 ?? $finalD2);

                    $dh = DataHomogenitas::create([
                        'sampel_inhouse_parameter_id' => $paramId,
                        'nomor_sampel' => $nomorSampel,
                        'nomor_botol_fisik' => $row['nomor_botol_fisik'] ?? null,
                        'urutan_instrumen_d1' => $row['urutan_d1'] ?? null,
                        'urutan_instrumen_d2' => $row['urutan_d2'] ?? null,
                        'data_mentah' => $mentahToSave, 
                        'nilai_d1' => $finalD1,
                        'nilai_d2' => $finalD2,
                        'mean_sampel' => $meanSampel,
                    ]);

                    $oldRow = $oldDataRows->get($nomorSampel);
                    if ($oldRow) {
                        // 1. Cek perubahan nilai persentase hasil akhir Duplo
                        $oldD1 = $oldRow->nilai_d1 !== null ? (float)$oldRow->nilai_d1 : null;
                        $newD1 = $finalD1 !== null ? (float)$finalD1 : null;
                        if ($oldD1 !== $newD1) {
                            $diffChanges["[{$namaParam}] Botol #{$nomorSampel} - D1"] = [
                                'old' => $oldD1 ?? '-',
                                'attributes' => $newD1 ?? '-'
                            ];
                        }
                        $oldD2 = $oldRow->nilai_d2 !== null ? (float)$oldRow->nilai_d2 : null;
                        $newD2 = $finalD2 !== null ? (float)$finalD2 : null;
                        if ($oldD2 !== $newD2) {
                            $diffChanges["[{$namaParam}] Botol #{$nomorSampel} - D2"] = [
                                'old' => $oldD2 ?? '-',
                                'attributes' => $newD2 ?? '-'
                            ];
                        }

                        $oldMentah = is_array($oldRow->data_mentah) ? $oldRow->data_mentah : [];
                        $allRawKeys = array_unique(array_merge(array_keys($oldMentah), array_keys($mentahToSave)));
                        foreach ($allRawKeys as $rk) {
                            if (in_array($rk, ['barang_ids', 'barang_jumlah', 'personil_ids', 'personil_peran', 'alat_ids', 'nilai_db_1', 'nilai_db_2'])) continue;
                            
                            $oldRk = $oldMentah[$rk] ?? null;
                            $newRk = $mentahToSave[$rk] ?? null;
                            
                            if ($oldRk != $newRk && ($oldRk !== null || $newRk !== null)) {
                                $diffChanges["[{$namaParam}] Botol #{$nomorSampel} - {$rk}"] = [
                                    'old' => ($oldRk !== null && $oldRk !== '') ? $oldRk : '-',
                                    'attributes' => ($newRk !== null && $newRk !== '') ? $newRk : '-'
                                ];
                            }
                        }
                    }


                    if ($d1_ada && $d2_ada) {
                        $val1 = $row['nilai_d1'];
                        $val2 = $row['nilai_d2'];
                        
                        $dbVal1 = $row['nilai_db_1'] ?? null;
                        $dbVal2 = $row['nilai_db_2'] ?? null;

                       $namaParamUp = strtoupper($param->parameterUji->nama_parameter);
                        $batchHasIm = $batch->parameters->contains(function ($p) {
                            return strtoupper($p->parameterUji->nama_parameter ?? '') === 'IM';
                        });
                        $hasImSource = $batchHasIm || !empty($batch->data_pemilihan_sampel['referensi_im']) || $request->filled('referensi_im_mode');

                        if (!in_array($namaParamUp, ['IM', 'AFT', 'CHN']) && $hasImSource) {
                            if ($dbVal1 === null || $dbVal1 === '' || $dbVal2 === null || $dbVal2 === '') {
                                throw new \Exception("Nilai IM untuk botol {$nomorSampel} belum terisi atau belum ditentukan. Hasil basis kering (db) pada parameter {$namaParamUp} tidak bisa dihitung.");
                            }
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

                $namaParamUpper = strtoupper($param->parameterUji->nama_parameter ?? '');
                if (in_array($namaParamUpper, ['AFT', 'CHN'])) {
                    $vals = [];
                    foreach ($inputData as $row) {
                        $mentah = $row['mentah'] ?? [];
                        if ($namaParamUpper === 'AFT') {
                            if (isset($mentah['st_1']) && $mentah['st_1'] !== '') $vals[] = floatval($mentah['st_1']);
                            if (isset($mentah['st_2']) && $mentah['st_2'] !== '') $vals[] = floatval($mentah['st_2']);
                            if (empty($mentah['st_1']) && isset($mentah['st']) && $mentah['st'] !== '') $vals[] = floatval($mentah['st']);
                        } elseif ($namaParamUpper === 'CHN') {
                            if (isset($mentah['c_1']) && $mentah['c_1'] !== '') $vals[] = floatval($mentah['c_1']);
                            if (isset($mentah['c_2']) && $mentah['c_2'] !== '') $vals[] = floatval($mentah['c_2']);
                            if (empty($mentah['c_1']) && isset($mentah['c']) && $mentah['c'] !== '') $vals[] = floatval($mentah['c']);
                        }
                    }
                    $count = count($vals);
                    $mean = $count > 0 ? array_sum($vals) / $count : 0;
                    $variance = 0;
                    if ($count > 1) {
                        foreach ($vals as $v) {
                            $variance += pow($v - $mean, 2);
                        }
                        $sd = sqrt($variance / ($count - 1));
                    } else {
                        $sd = 0;
                    }
                    $param->update([
                        'mean_global' => round($mean, 2),
                        'sd_global' => round($sd, 2),
                        'f_hitung' => 1,
                        'f_tabel' => 3.02,
                        'status_parameter' => 'homogen',
                        'tanggal_homogenitas' => now(),
                    ]);
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

                if (!$oldDataRows->isEmpty() && $oldParamStatus && $oldParamStatus !== ($isHomogen ? 'homogen' : 'tidak_homogen')) {
                    $diffChanges["[{$namaParam}] Status Homogenitas"] = [
                        'old' => $oldParamStatus,
                        'attributes' => $isHomogen ? 'homogen' : 'tidak_homogen'
                    ];
                }
                if (!$oldDataRows->isEmpty() && $oldFHitung !== null && (float)$oldFHitung != (float)$anova['f_hitung']) {
                    $diffChanges["[{$namaParam}] F-Hitung"] = [
                        'old' => round((float)$oldFHitung, 3),
                        'attributes' => round((float)$anova['f_hitung'], 3)
                    ];
                }
            }

            if (!$adaData) {
                throw new \Exception("Data penimbangan tidak lengkap.");
            }

            $semuaParameterLengkap = true;
            $semuaParameterHomogen = true;
            $gagalHomogenParams = [];
            
            $batch->refresh();
            foreach ($batch->parameters as $p) {
                if ($p->status_parameter !== 'homogen') {
                    $semuaParameterHomogen = false;
                    if ($p->status_parameter === 'tidak_homogen') {
                        $gagalHomogenParams[] = $p->parameterUji->nama_parameter ?? 'Parameter';
                    }
                }
                if (in_array($p->status_parameter, [null, 'pending', 'draft'])) {
                    $semuaParameterLengkap = false;
                }
            }

            if ($semuaParameterLengkap || (!$request->ajax() && $semuaHomogen)) {
                $batch->update([
                    'status' => $semuaParameterHomogen ? 'penetapan_target' : 'gagal_homogenitas',
                ]);

                // Jika terdeteksi Tidak Homogen, kirim notifikasi lonceng
                if (!$semuaParameterHomogen) {
                    $paramString = !empty($gagalHomogenParams) ? implode(', ', $gagalHomogenParams) : '';
                    $pesanNotif = "⚠️ Gagal Homogenitas: Sampel {$batch->kode_batch} ({$batch->nama_sampel}) dinyatakan TIDAK HOMOGEN" . ($paramString ? " pada parameter {$paramString}." : ".") . " Silakan lakukan investigasi.";
                    $urlNotif = route('qc-inhouse.show', $id);

                    $targetUsers = \App\Models\User::whereHas('role', function($q) {
                        $q->where('nama_role', 'LIKE', '%Analis%')
                          ->orWhere('nama_role', 'LIKE', '%Koordinator%');
                    })->where('status_aktif', true)->get();

                    $notifData = [];
                    foreach ($targetUsers as $u) {
                        $notifData[] = [
                            'users_id' => $u->users_id,
                            'jenis_notifikasi' => 'qc',
                            'pesan' => $pesanNotif,
                            'url' => $urlNotif,
                            'is_read' => 0,
                            'created_at' => now(),
                        ];
                    }

                    if (!empty($notifData)) {
                        \Illuminate\Support\Facades\DB::table('notifikasi')->insert($notifData);
                    }
                }
            }

            $isDraft = (bool) $request->input('is_draft');

            if ($isDraft) {
                $logDeskripsi = "Menyimpan draft data pengujian homogenitas batch {$batch->kode_batch}";
            } elseif ($batch->status === 'penetapan_target') {
                $logDeskripsi = "Menyelesaikan uji homogenitas batch {$batch->kode_batch}: LULUS HOMOGEN (Lanjut Penetapan Target)";
            } elseif ($batch->status === 'gagal_homogenitas') {
                $logDeskripsi = "Menyelesaikan uji homogenitas batch {$batch->kode_batch}: TIDAK HOMOGEN";
            } else {
                $logDeskripsi = "Memperbarui data pengujian homogenitas batch {$batch->kode_batch}";
            }

            if ($oldBatchStatus !== $batch->status) {
                $diffChanges["Status Tahap Batch"] = [
                    'old' => $oldBatchStatus,
                    'attributes' => $batch->status
                ];
            }

            $oldLogData = [];
            $newLogData = [];
            foreach ($diffChanges as $col => $diff) {
                $oldLogData[$col] = $diff['old'];
                $newLogData[$col] = $diff['attributes'];
            }

            $act = activity()
                ->performedOn($batch)
                ->causedBy(Auth::user())
                ->event('updated');

            if (!empty($oldLogData) || !empty($newLogData)) {
                $act->withChanges([
                    'old' => $oldLogData,
                    'attributes' => $newLogData
                ]);
            }

            $act->log($logDeskripsi);

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

    public function showPenetapanTarget($id)
    {
        $batch = SampelInhouse::with(['parameters.parameterUji', 'parameters.dataPenetapanTarget'])->findOrFail($id);
        $this->sortBatchParameters($batch);
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

            activity()
                ->performedOn($batch)
                ->causedBy(Auth::user())
                ->event('updated')
                ->log("Mengesahkan penetapan nilai target dan batas kendali batch {$batch->kode_batch} (Lanjut ke Uji Stabilitas)");

            DB::commit();
            return redirect()->route('qc-inhouse.stabilitas', $id)
                             ->with('success', 'Nilai target berhasil disahkan dari data Uji Homogenitas. Lanjut ke Uji Stabilitas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengesahkan nilai target: ' . $e->getMessage());
        }
    }

    public function showStabilitas($id)
    {
        $batch = SampelInhouse::with(['parameters.parameterUji', 'parameters.dataStabilitas', 'parameters.dataHomogenitas'])->findOrFail($id);

        $this->sortBatchParameters($batch);
        
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

            $oldBatchStatus = $batch->status;
            $diffChanges = [];

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

                $namaParam = $param->parameterUji->nama_parameter ?? 'Param';

                $oldDataRows = DataStabilitas::where('sampel_inhouse_parameter_id', $paramId)
                    ->get()
                    ->keyBy('nomor_pengujian');

                $oldParamStatus = $param->status_parameter;
                $oldTHitung = $param->t_hitung;
                
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
                    $namaParamUpper = strtoupper($param->parameterUji->nama_parameter ?? '');
                    if ($namaParamUpper === 'AFT') {
                        $val1 = isset($row['mentah']['st_1']) && $row['mentah']['st_1'] !== '' ? floatval($row['mentah']['st_1']) : null;
                        $val2 = isset($row['mentah']['st_2']) && $row['mentah']['st_2'] !== '' ? floatval($row['mentah']['st_2']) : null;
                    } elseif ($namaParamUpper === 'CHN') {
                        $val1 = isset($row['mentah']['c_1']) && $row['mentah']['c_1'] !== '' ? floatval($row['mentah']['c_1']) : null;
                        $val2 = isset($row['mentah']['c_2']) && $row['mentah']['c_2'] !== '' ? floatval($row['mentah']['c_2']) : null;
                    }

                    $val1 = ($val1 === '') ? null : $val1;
                    $val2 = ($val2 === '') ? null : $val2;
                    $lengkap = ($val1 !== null && $val2 !== null);
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

                    $oldRow = $oldDataRows->get($index + 1);
                    if ($oldRow) {
                        $oldD1 = $oldRow->nilai_d1 !== null ? (float)$oldRow->nilai_d1 : null;
                        $newD1 = $val1 !== null ? (float)$val1 : null;
                        if ($oldD1 !== $newD1) {
                            $diffChanges["[{$namaParam}] Pengujian #" . ($index + 1) . " - D1"] = [
                                'old' => $oldD1 ?? '-',
                                'attributes' => $newD1 ?? '-'
                            ];
                        }

                        $oldD2 = $oldRow->nilai_d2 !== null ? (float)$oldRow->nilai_d2 : null;
                        $newD2 = $val2 !== null ? (float)$val2 : null;
                        if ($oldD2 !== $newD2) {
                            $diffChanges["[{$namaParam}] Pengujian #" . ($index + 1) . " - D2"] = [
                                'old' => $oldD2 ?? '-',
                                'attributes' => $newD2 ?? '-'
                            ];
                        }

                        $oldMentah = is_array($oldRow->data_mentah) ? $oldRow->data_mentah : [];
                        $newMentah = $row['mentah'] ?? [];
                        $allRawKeys = array_unique(array_merge(array_keys($oldMentah), array_keys($newMentah)));
                        foreach ($allRawKeys as $rk) {
                            if (in_array($rk, ['barang_ids', 'barang_jumlah', 'personil_ids', 'personil_peran', 'alat_ids', 'nilai_db_1', 'nilai_db_2', 'tanggal_uji', 'analis'])) continue;
                            $oldRk = $oldMentah[$rk] ?? null;
                            $newRk = $newMentah[$rk] ?? null;
                            if ($oldRk != $newRk && ($oldRk !== null || $newRk !== null)) {
                                $diffChanges["[{$namaParam}] Pengujian #" . ($index + 1) . " - {$rk}"] = [
                                    'old' => ($oldRk !== null && $oldRk !== '') ? $oldRk : '-',
                                    'attributes' => ($newRk !== null && $newRk !== '') ? $newRk : '-'
                                ];
                            }
                        }
                    }

                    if ($lengkap) {
                        $isDirect = in_array(strtoupper($param->parameterUji->nama_parameter), ['IM', 'AFT', 'CHN']);
                        $db1 = $row['nilai_db_1'] ?? '';
                        $db2 = $row['nilai_db_2'] ?? '';

                        if ($isDirect) {
                            $stabilityDataY[] = (float) $val1;
                            $stabilityDataY[] = (float) $val2;
                        } elseif ($db1 !== '' && $db2 !== '') {
                            $stabilityDataY[] = (float) $db1;
                            $stabilityDataY[] = (float) $db2;
                        }
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
                        if (!in_array($namaParam, ['IM', 'AFT', 'CHN']) && isset($imByNo[$dh->nomor_sampel][$k])) {
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

                if (!$oldDataRows->isEmpty() && $oldParamStatus && $oldParamStatus !== ($isStabil ? 'stabil' : 'tidak_stabil')) {
                    $diffChanges["[{$namaParam}] Status Stabilitas"] = [
                        'old' => $oldParamStatus,
                        'attributes' => $isStabil ? 'stabil' : 'tidak_stabil'
                    ];
                }
                if (!$oldDataRows->isEmpty() && $oldTHitung !== null && (float)$oldTHitung != (float)$tTest['t_hitung']) {
                    $diffChanges["[{$namaParam}] T-Hitung"] = [
                        'old' => round((float)$oldTHitung, 3),
                        'attributes' => round((float)$tTest['t_hitung'], 3)
                    ];
                }
            }

            if (!$adaData) {
                throw new \Exception("Data pengujian stabilitas kosong.");
            }

            $allStabil = true;
            $adaGagal = false;
            $gagalParams = [];

            foreach ($batch->parameters as $p) {
                $p->refresh();
                if ($p->status_parameter === 'tidak_stabil') {
                    $adaGagal = true;
                    $gagalParams[] = $p->parameterUji->nama_parameter ?? 'Parameter';
                }
                if ($p->status_parameter !== 'stabil') {
                    $allStabil = false;
                }
            }

            if ($adaGagal) {
                $batch->update(['status' => 'gagal_stabilitas']);

                // Kirim notifikasi lonceng untuk Gagal Stabilitas
                $paramString = !empty($gagalParams) ? implode(', ', $gagalParams) : '';
                $pesanNotif = "⚠️ Gagal Stabilitas: Sampel {$batch->kode_batch} ({$batch->nama_sampel}) dinyatakan TIDAK STABIL" . ($paramString ? " pada parameter {$paramString}." : ".") . " Silakan lakukan investigasi.";
                $urlNotif = route('qc-inhouse.show', $id);

                $targetUsers = \App\Models\User::whereHas('role', function($q) {
                    $q->where('nama_role', 'LIKE', '%Analis%')
                      ->orWhere('nama_role', 'LIKE', '%Koordinator%');
                })->where('status_aktif', true)->get();

                $notifData = [];
                foreach ($targetUsers as $u) {
                    $notifData[] = [
                        'users_id' => $u->users_id,
                        'jenis_notifikasi' => 'qc',
                        'pesan' => $pesanNotif,
                        'url' => $urlNotif,
                        'is_read' => 0,
                        'created_at' => now(),
                    ];
                }

                if (!empty($notifData)) {
                    \Illuminate\Support\Facades\DB::table('notifikasi')->insert($notifData);
                }

            } elseif ($allStabil) {
                $batch->update(['status' => 'siap_digunakan']);
            } else {
                $batch->update(['status' => 'uji_stabilitas']);
            }

            $isDraft = (bool) $request->input('is_draft');
            if ($isDraft) {
                $logDeskripsi = "Menyimpan draft data pengujian stabilitas batch {$batch->kode_batch}";
            } elseif ($allStabil) {
                $logDeskripsi = "Menyelesaikan uji stabilitas batch {$batch->kode_batch}: STABIL (Sampel Siap Digunakan)";
            } elseif ($adaGagal) {
                $logDeskripsi = "Menyelesaikan uji stabilitas batch {$batch->kode_batch}: TIDAK STABIL";
            } else {
                $logDeskripsi = "Memperbarui data pengujian stabilitas batch {$batch->kode_batch}";
            }

            if ($oldBatchStatus !== $batch->status) {
                $diffChanges["Status Tahap Batch"] = [
                    'old' => $oldBatchStatus,
                    'attributes' => $batch->status
                ];
            }

            $oldLogData = [];
            $newLogData = [];
            foreach ($diffChanges as $col => $diff) {
                $oldLogData[$col] = $diff['old'];
                $newLogData[$col] = $diff['attributes'];
            }

            $act = activity()
                ->performedOn($batch)
                ->causedBy(Auth::user())
                ->event('updated');

            if (!empty($oldLogData) || !empty($newLogData)) {
                $act->withChanges([
                    'old' => $oldLogData,
                    'attributes' => $newLogData
                ]);
            }

            $act->log($logDeskripsi);

            DB::commit();

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

        $this->sortBatchParameters($batch);
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
            \App\Models\ParameterUji::withoutEvents(function () use ($batch) {

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
            });

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

    private function sortBatchParameters($batch)
    {
        $order = ['IM' => 1, 'ASH' => 2, 'VM' => 3, 'TS' => 4, 'CV' => 5, 'AFT' => 6, 'CHN' => 7];
        $sorted = $batch->parameters->sortBy(function ($p) use ($order) {
            $code = trim(strtoupper($p->parameterUji->nama_parameter ?? ''));
            return $order[$code] ?? 99;
        })->values();
        $batch->setRelation('parameters', $sorted);
    }
}