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
        $filterStatus = $request->input('filter_status');
        $filterJenis = $request->input('filter_jenis');

        $query = SampelInhouse::with(['parameters.parameterUji', 'pembuat'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where('nama_sampel', 'like', "%{$search}%")
                  ->orWhere('kode_batch', 'like', "%{$search}%");
        }

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        if ($filterJenis) {
            $query->where('jenis_batubara', $filterJenis);
        }

        $sampels = $query->paginate(15);
        return view('qc-inhouse.index', compact('sampels'));
    }

    // ============================================
    // TAHAP 1: PEMILIHAN SAMPEL
    // ============================================
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

        // 1. Hukum Fisika & Proximate Balance
        // Hitung FC = 100 - (MAD + Ash + VM)
        $fc = 100 - ((float)$request->mad + (float)$request->ash + (float)$request->vm);
        $dataScreening['fc'] = round($fc, 4);

        $crossErrors = $this->pemilihanSampelService->validateCrossField($dataScreening);
        if (!empty($crossErrors)) {
            return redirect()->back()->withInput()->withErrors($crossErrors);
        }

        // 2. Konversi ADB -> DB
        $dbData = $this->pemilihanSampelService->convertAdbToDb($dataScreening);
        $dataScreening = array_merge($dataScreening, $dbData);

        // 3. Validasi Rentang Komoditas
        $rangeErrors = $this->pemilihanSampelService->validateRange($jenis, $dataScreening);
        if (!empty($rangeErrors)) {
            return redirect()->back()->withInput()->withErrors($rangeErrors);
        }

        DB::beginTransaction();
        try {
            // Create Batch Header
            $batch = SampelInhouse::create([
                'nama_sampel' => $request->nama_sampel,
                'jenis_batubara' => $jenis,
                'data_screening' => $dataScreening, // Simpan profil kasar
                'tanggal_pemilihan' => now(),
                'status' => 'preparasi', // Auto lanjut ke preparasi
                'dibuat_oleh' => Auth::id(),
            ]);

            // Ambil ID Parameter untuk 5 General Analysis
            $namaParamGa = ['IM', 'ASH', 'VM', 'TS', 'CV'];
            $params = ParameterUji::whereIn('nama_parameter', $namaParamGa)->get();

            // Auto-bundling 5 Parameter (Eliminasi TM)
            foreach ($params as $p) {
                SampelInhouseParameter::create([
                    'sampel_inhouse_id' => $batch->sampel_inhouse_id,
                    'parameter_uji_id' => $p->parameter_uji_id,
                    'status_parameter' => 'draft',
                ]);
            }

            DB::commit();

            return redirect()->route('qc-inhouse.preparasi', $batch->sampel_inhouse_id)
                             ->with('success', 'Screening Tahap 1 lolos. Profil dan 5 parameter uji (MAD, Ash, VM, TS, GCV) telah diteruskan ke Tahap 2.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan sampel: ' . $e->getMessage());
        }
    }

    // ============================================
    // TAHAP 2: PREPARASI SAMPEL
    // ============================================
    public function showPreparasi($id)
    {
        $batch = SampelInhouse::with('parameters.parameterUji')->findOrFail($id);
        
        if (!in_array($batch->status, ['preparasi', 'pemilihan_sampel'])) {
            return redirect()->route('qc-inhouse.show', $id)
                             ->with('error', 'Sampel ini sudah melewati tahap preparasi.');
        }

        return view('qc-inhouse.preparasi', compact('batch'));
    }

    public function storePreparasi(Request $request, $id)
    {
        $batch = SampelInhouse::findOrFail($id);

        $request->validate([
            'metode_acuan' => 'required|in:astm,iso',
            'jumlah_botol' => 'required|integer|min:10',
            'nomor_awal_botol' => 'required|integer|min:1',
            'kode_batch' => 'required|string|max:50',
            'data_equilibrium' => 'required|string', // JSON dari frontend
            'catatan_preparasi' => 'nullable|string'
        ]);

        $dataEquilibrium = json_decode($request->data_equilibrium, true) ?? [];
        $konstan = $this->preparasiService->isEquilibriumReached($dataEquilibrium);

        if (!$konstan) {
            return redirect()->back()->withInput()->with('error', 'Bobot konstan (selisih <= 0.001) belum tercapai.');
        }

        // Generate urutan acak instrumen untuk 20 porsi (10 botol x 2)
        $urutanInstrumen = $this->preparasiService->generateUrutanInstrumen(10, 2);

        $batch->update([
            'metode_acuan' => $request->metode_acuan,
            'kode_batch' => $request->kode_batch,
            'jumlah_botol' => $request->jumlah_botol,
            'nomor_awal_botol' => $request->nomor_awal_botol,
            'data_equilibrium' => $dataEquilibrium,
            'bobot_konstan_tercapai' => true,
            'catatan_preparasi' => $request->catatan_preparasi,
            'tanggal_preparasi' => now(),
            'dipreparasi_oleh' => Auth::id(),
            'urutan_acak_instrumen' => $urutanInstrumen,
            'status' => 'uji_homogenitas'
        ]);

        return redirect()->route('qc-inhouse.cetak-label', $batch->sampel_inhouse_id)
                         ->with('success', 'Preparasi selesai. Silakan cetak label botol.');
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

    // ============================================
    // TAHAP 3: UJI HOMOGENITAS
    // ============================================
    public function showInstruksiHomogenitas($id)
    {
        $batch = SampelInhouse::findOrFail($id);
        $tabelAcak = TabelAngkaAcak::orderBy('urutan')->get();

        return view('qc-inhouse.instruksi-homogenitas', compact('batch', 'tabelAcak'));
    }

    public function showHomogenitas($id)
    {
        $batch = SampelInhouse::with(['parameters.parameterUji', 'parameters.dataHomogenitas'])->findOrFail($id);
        
        // Populate tolerance limits for UI
        $tolerances = [];
        foreach ($batch->parameters as $param) {
            // Kita pakai batas sementara dengan asumsi rata-rata = 0, akan diupdate oleh JS live
            $tolerances[$param->id] = $this->repeatabilityService->getLimit(
                $batch->metode_acuan, 
                $param->parameterUji->nama_parameter, 
                0, 
                $batch->jenis_batubara
            );
        }

        $tabelAcak = TabelAngkaAcak::orderBy('urutan')->get()->keyBy('urutan');

        return view('qc-inhouse.homogenitas', compact('batch', 'tolerances', 'tabelAcak'));
    }

    public function storeHomogenitas(Request $request, $id)
    {
        $batch = SampelInhouse::with('parameters')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            $semuaHomogen = true;
            $adaData = false;

            foreach ($batch->parameters as $param) {
                $paramId = $param->id;
                $inputData = $request->input("data_{$paramId}");
                
                if (empty($inputData) || count($inputData) < 10) continue;
                $adaData = true;

                // Kosongkan data lama
                DataHomogenitas::where('sampel_inhouse_parameter_id', $paramId)->delete();
                
                $samplesForAnova = [];
                $isiLengkap = 0;

                foreach ($inputData as $index => $row) {
                    $nomorSampel = $index + 1; // 1-10
                    
                    // Cek apakah d1 dan d2 terisi angka (bukan string kosong)
                    $d1_ada = isset($row['nilai_d1']) && $row['nilai_d1'] !== '';
                    $d2_ada = isset($row['nilai_d2']) && $row['nilai_d2'] !== '';
                    
                    if ($d1_ada && $d2_ada) {
                        $isiLengkap++;
                    }

                    $dh = DataHomogenitas::create([
                        'sampel_inhouse_parameter_id' => $paramId,
                        'nomor_sampel' => $nomorSampel,
                        'nomor_botol_fisik' => $row['nomor_botol_fisik'] ?? null,
                        'urutan_instrumen_d1' => $row['urutan_d1'] ?? null,
                        'urutan_instrumen_d2' => $row['urutan_d2'] ?? null,
                        'data_mentah' => $row['mentah'] ?? [], 
                        'nilai_d1' => $d1_ada ? $row['nilai_d1'] : null,
                        'nilai_d2' => $d2_ada ? $row['nilai_d2'] : null,
                        'mean_sampel' => ($d1_ada && $d2_ada) ? ($row['nilai_d1'] + $row['nilai_d2']) / 2 : null,
                    ]);

                    if ($d1_ada && $d2_ada) {
                        $val1 = $row['nilai_d1'];
                        $val2 = $row['nilai_d2'];
                        
                        if (isset($row['nilai_db_1']) && $row['nilai_db_1'] !== '') {
                            $val1 = $row['nilai_db_1'];
                            $val2 = $row['nilai_db_2'];
                        }

                        $samplesForAnova[] = [
                            'nilai_d1' => $val1,
                            'nilai_d2' => $val2
                        ];
                    }
                }

                // Jika simpan draft, lewati kalkulasi ANOVA
                if ($request->input('is_draft')) {
                    continue; // Pindah ke parameter berikutnya, tidak ubah status_parameter
                }

                // Pastikan 10 data lengkap untuk ANOVA
                if ($isiLengkap < 10) {
                    throw new \Exception("Parameter {$param->parameterUji->nama_parameter} belum lengkap 10 sampel.");
                }

                // Kalkulasi ANOVA
                $anova = $this->homogenitasService->calculateAnova($samplesForAnova);
                
                if (isset($anova['error'])) {
                    throw new \Exception($anova['message']);
                }

                $isHomogen = $anova['lolos'];
                if (!$isHomogen) $semuaHomogen = false;

                $param->update([
                    'mean_global' => $anova['mean_global'],
                    'sd_global' => $anova['sd_global'],
                    'f_hitung' => $anova['f_hitung'],
                    'f_tabel' => $anova['f_tabel'],
                    'status_parameter' => $isHomogen ? 'homogen' : 'tidak_homogen',
                    'tanggal_homogenitas' => now(),
                ]);
            }

            if (!$adaData) {
                throw new \Exception("Data penimbangan tidak lengkap.");
            }

            // Cek apakah semua parameter sudah diuji
            $semuaParameterLengkap = true;
            $semuaParameterHomogen = true;
            
            // Re-fetch parameters to get updated status
            $batch->refresh();
            foreach ($batch->parameters as $p) {
                if ($p->status_parameter !== 'homogen') {
                    $semuaParameterHomogen = false;
                }
                if (in_array($p->status_parameter, [null, 'pending', 'draft'])) {
                    $semuaParameterLengkap = false;
                }
            }

            // Update Batch Status hanya jika semua lengkap (atau jika form disubmit penuh non-ajax)
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
        
        DB::beginTransaction();
        try {
            foreach ($batch->parameters as $param) {
                // Sesuai prosedur baru: Nilai Target & SD diambil langsung dari Mean Global & SD Global uji Homogenitas
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

        return view('qc-inhouse.stabilitas', compact('batch', 'tolerances', 'sisaBotol'));
    }

    public function storeStabilitas(Request $request, $id)
    {
        $batch = SampelInhouse::with(['parameters.dataPenetapanTarget', 'parameters.dataHomogenitas'])->findOrFail($id);
        
        DB::beginTransaction();
        try {
            $semuaStabil = true;
            $adaData = false;

            foreach ($batch->parameters as $param) {
                $paramId = $param->id;
                $inputData = $request->input("data_{$paramId}");
                
                if (empty($inputData)) continue;
                $adaData = true;

                DataStabilitas::where('sampel_inhouse_parameter_id', $paramId)->delete();
                
                $stabilityDataY = [];
                foreach ($inputData as $index => $row) {
                    $val1 = $row['nilai_d1'] ?? null;
                    $val2 = $row['nilai_d2'] ?? null;
                    if ($val1 === null || $val1 === '' || $val2 === null || $val2 === '') continue;

                    $meanPengujian = ($val1 + $val2) / 2;
                    
                    DataStabilitas::create([
                        'sampel_inhouse_parameter_id' => $paramId,
                        'nomor_pengujian' => $index + 1,
                        'nomor_botol_fisik' => $row['nomor_botol_fisik'] ?? null,
                        'data_mentah' => array_merge(
                            $row['mentah'] ?? [],
                            [
                                'tanggal_uji' => $request->input("kondisi.tanggal"),
                                'analis' => $request->input("kondisi.analis"),
                            ]
                        ),
                        'nilai_d1' => $val1,
                        'nilai_d2' => $val2,
                        'mean_pengujian' => $meanPengujian,
                    ]);

                    // Gunakan nilai DB jika tersedia untuk T-Test
                    if (isset($row['nilai_db_1']) && $row['nilai_db_1'] !== '') {
                        $val1 = $row['nilai_db_1'];
                        $val2 = $row['nilai_db_2'];
                    }
                    $stabilityDataY[] = (float)$val1;
                    $stabilityDataY[] = (float)$val2;
                }

                // Karena Target ditetapkan dari Homogenitas (N=20), kita bisa reverse-engineer sum_sq dari sd_target
                // Rumus: SD = sqrt(sum_sq / (N - 1)) -> sum_sq = (SD^2) * (N - 1)
                $nx = 20;
                $targetDataset = [
                    'n' => $nx,
                    'mean' => (float)$param->mean_target,
                    'sum_sq' => pow((float)$param->sd_target, 2) * ($nx - 1)
                ];

                $tTest = $this->stabilitasService->calculateTTest($stabilityDataY, $targetDataset);
                
                if (isset($tTest['error'])) {
                    throw new \Exception($tTest['message']);
                }

                $isStabil = $tTest['lolos'];
                if (!$isStabil) $semuaStabil = false;

                $param->update([
                    'mean_stabilitas' => $tTest['mean_stabilitas'],
                    'sd_stabilitas' => $tTest['sd_stabilitas'],
                    't_hitung' => $tTest['t_hitung'],
                    't_tabel' => $tTest['t_tabel'],
                    'status_parameter' => $isStabil ? 'stabil' : 'tidak_stabil',
                    'tanggal_stabilitas' => now(),
                ]);

                // Jika lolos semua, update Master Parameter Uji Limit
                if ($isStabil) {
                    $param->parameterUji->update([
                        'sampel_inhouse_id' => $batch->sampel_inhouse_id, // Link to active batch
                        'mean' => $param->mean_target,
                        'sd' => $param->sd_target,
                        'ucl' => $param->mean_target + (3 * $param->sd_target),
                        'lcl' => $param->mean_target - (3 * $param->sd_target),
                        'uwl' => $param->mean_target + (2 * $param->sd_target),
                        'lwl' => $param->mean_target - (2 * $param->sd_target),
                    ]);
                }
            }

            if (!$adaData) {
                throw new \Exception("Data pengujian stabilitas kosong.");
            }

            // Check if ALL parameters are stabil before updating batch status
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
                // Batch stabil tapi belum aktif secara harian (menunggu aktivasi manual)
                $batch->update(['status' => 'siap_digunakan']);
            } else {
                // Return to uji_stabilitas if someone partially saved and the batch prematurely became 'aktif'
                $batch->update(['status' => 'uji_stabilitas']);
            }

            DB::commit();

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

        } catch (\Exception $e) {
            DB::rollBack();
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

        if ($batch->status !== 'siap_digunakan') {
            return back()->with('error', 'Status sampel tidak valid untuk diaktifkan.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Nonaktifkan batch lain agar HANYA ADA 1 BATCH AKTIF pada satu waktu
            SampelInhouse::where('status', 'aktif')
                ->where('sampel_inhouse_id', '!=', $batch->sampel_inhouse_id)
                ->update(['status' => 'kadaluarsa']);
                
            $batch->update(['status' => 'aktif']);

            // SINKRONISASI KE MASTER DATA PARAMETER UJI
            // Analis meminta agar setelah diaktifkan, nilai acuan & SD di Master Parameter 
            // otomatis terganti dengan hasil dari Batch yang baru ini.
            foreach ($batch->parameters as $param) {
                if ($param->status_parameter === 'stabil' && $param->parameterUji) {
                    $mean = (float) $param->mean_target;
                    $sd = (float) $param->sd_target;

                    $param->parameterUji->update([
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
}
