<?php

namespace App\Http\Controllers;

use App\Models\QcHarian;
use App\Models\SampelInhouse;
use App\Models\ParameterUji;
use App\Services\WestgardService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class QcHarianController extends Controller
{
    protected $westgard;

    public function __construct(WestgardService $westgard)
    {
        $this->westgard = $westgard;
    }

    public function index()
    {
        $activeBatch = SampelInhouse::where('status', 'aktif')->latest()->first();
        $parameters = collect();
        
        $groupedLogs = collect();

        if ($activeBatch) {
            $parameters = $activeBatch->parameters()->where('status_parameter', 'stabil')->get();
            
            $recentLogs = QcHarian::where('sampel_inhouse_id', $activeBatch->sampel_inhouse_id)
                ->with(['parameterUji', 'analis'])
                ->orderBy('tanggal_uji', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $groupedLogs = $recentLogs->groupBy(function($log) {
                if ($log->status_pengujian === 'draft' && $log->draft_group_id) {
                    return 'DRAFT-' . $log->draft_group_id; 
                }
                return 'SELESAI-' . $log->created_at->format('Y-m-d-H-i');
            });
        }
        return view('qc-harian.index', compact('activeBatch', 'parameters', 'groupedLogs'));
    }

    public function create()
    {
        $activeBatch = SampelInhouse::where('status', 'aktif')->latest()->first();
        if (!$activeBatch) {
            return redirect()->route('qc-harian.index')->with('error', 'Tidak ada sampel QC In-House yang berstatus aktif.');
        }

        $parameters = $activeBatch->parameters()->where('status_parameter', 'stabil')->with('parameterUji')->get();
        $alatList = \App\Models\Alat::where('kondisi_barang', 'baik')->orderBy('nama_alat')->get();
        $personilList = \App\Models\Personil::orderBy('nama')->get();
        $barangList = \App\Models\Barang::orderBy('nama_barang')->get();
        
        

        return view('qc-harian.create', compact('activeBatch', 'parameters', 'alatList', 'personilList', 'barangList'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'tanggal_uji' => 'required|date',
            'params' => 'required|array',
        ]);

        $activeBatch = SampelInhouse::where('status', 'aktif')->latest()->first();
        if (!$activeBatch) {
            return back()->with('error', 'Tidak ada batch aktif.');
        }

        $isDraft = $request->input('is_draft') == 1;
        $draftGroupId = $isDraft 
            ? ($request->filled('old_draft_group_id') ? $request->input('old_draft_group_id') : uniqid('DRF-')) 
            : null;
        $selectedParamIds = [];
        if (is_array($request->input('params'))) {
            foreach ($request->input('params') as $pId => $pData) {
                if (!empty($pData['selected'])) {
                    $selectedParamIds[] = $pId;
                }
            }
        }

        if ($request->filled('old_draft_group_id')) {
            QcHarian::where('draft_group_id', $request->old_draft_group_id)
                ->whereNotIn('parameter_uji_id', $selectedParamIds)
                ->delete();
        }

        $outlierParams = [];
        $savedCount = 0;
        $results = [];

        $section1 = [
            'nama_sampel_uji' => $request->input('nama_sampel_uji'),
        ];

        $globalIm1 = 0;
        $globalIm2 = 0;
        if (is_array($request->input('params'))) {
            foreach ($request->input('params') as $pId => $pData) {
                if (empty($pData['selected'])) continue;
                $pUji = ParameterUji::find($pId);
                if ($pUji && strtoupper($pUji->nama_parameter) === 'IM') {
                    $globalIm1 = (float)($pData['d1'] ?? 0);
                    $globalIm2 = (float)($pData['d2'] ?? 0);
                    break;
                }
            }
        }

        foreach ($request->input('params') as $paramUjiId => $data) {
            if (empty($data['selected'])) continue;

            $paramUji = ParameterUji::find($paramUjiId);
            if (!$paramUji) continue;

            // if (!$isDraft) {
            //     $locked = QcHarian::where('sampel_inhouse_id', $activeBatch->sampel_inhouse_id)
            //         ->where('parameter_uji_id', $paramUji->parameter_uji_id)
            //         ->where('status_evaluasi', 'outlier')
            //         ->where('status_investigasi', 'menunggu_investigasi')
            //         ->exists();
            //     if ($locked) continue;
            // }

            $d1 = (float)($data['d1'] ?? 0);
            $d2 = (float)($data['d2'] ?? 0);
            $db1 = null;
            $db2 = null;

            $pName = strtoupper($paramUji->nama_parameter);
            $needsDb = in_array($pName, ['ASH', 'VM', 'CV', 'TS', 'FC']);

            $mentah = $data['mentah'] ?? [];
            $mentah = array_merge($mentah, $section1);

            $evalStatus = 'draft';
            $evalRule = null;
            $evalNilaiAkhir = null;

            if ($needsDb) {
                $im1 = (float)($data['mentah']['im_d1'] ?? $globalIm1);
                $im2 = (float)($data['mentah']['im_d2'] ?? $globalIm2);
                $mentah['im_d1'] = $im1;
                $mentah['im_d2'] = $im2;

                if (!$isDraft) {
                    if ($im1 >= 100 || $im2 >= 100) continue;

                    $frontendDb1 = isset($data['db1']) && $data['db1'] !== '' ? (float)$data['db1'] : null;
                    $frontendDb2 = isset($data['db2']) && $data['db2'] !== '' ? (float)$data['db2'] : null;

                    $db1 = $frontendDb1 ?? ((100 / (100 - $im1)) * $d1);
                    $db2 = $frontendDb2 ?? ((100 / (100 - $im2)) * $d2);

                    $val1 = $db1;
                    $val2 = $db2;
                }
            } else {
                if (!$isDraft) {
                    $val1 = $d1;
                    $val2 = $d2;
                }
            }

            $meanAcuan = $paramUji->mean;
            $sdAcuan = $paramUji->sd;

            if (!$isDraft && $meanAcuan !== null && $sdAcuan !== null) {
                $history = QcHarian::where('sampel_inhouse_id', $activeBatch->sampel_inhouse_id)
                    ->where('parameter_uji_id', $paramUji->parameter_uji_id)
                    ->where('status_pengujian', 'selesai')
                    ->orderBy('tanggal_uji', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->take(9)->get();

                $eval = $this->westgard->evaluate($val1, $val2, $meanAcuan, $sdAcuan, $history);
                $evalStatus = $eval['status'];
                $evalRule = $eval['rule'] ? $eval['message'] : null;
                $evalNilaiAkhir = $eval['nilai_akhir'];
            }

            $isRetest = false;
            if (!$isDraft) {
                $lastRecord = QcHarian::where('sampel_inhouse_id', $activeBatch->sampel_inhouse_id)
                    ->where('parameter_uji_id', $paramUji->parameter_uji_id)
                    ->where('status_pengujian', 'selesai')
                    ->latest('id')
                    ->first();

                if ($lastRecord && $lastRecord->status_evaluasi === 'outlier' && $lastRecord->status_investigasi === 'selesai_investigasi') {
                    $isRetest = true;
                }
            }

            $attributes = [
                'sampel_inhouse_id' => $activeBatch->sampel_inhouse_id,
                'parameter_uji_id' => $paramUji->parameter_uji_id,
                'tanggal_uji' => $request->tanggal_uji,
                'analis_id' => auth()->id(),
                'nilai_d1' => $d1,
                'nilai_d2' => $d2,
                'nilai_db_1' => $db1,
                'nilai_db_2' => $db2,
                'nilai_akhir' => $evalNilaiAkhir,
                'mean_acuan' => $meanAcuan,
                'sd_acuan' => $sdAcuan,
                'status_evaluasi' => $evalStatus,
                'status_pengujian' => $isDraft ? 'draft' : 'selesai',
                'draft_group_id' => $draftGroupId,
                'pelanggaran_rule' => $evalRule,
                'status_investigasi' => $evalStatus === 'outlier' ? 'menunggu_investigasi' : 'aman',
                'data_mentah' => $mentah,
                'is_retest' => $isRetest,
            ];

            $qch = null;
            if ($request->filled('old_draft_group_id')) {
                $qch = QcHarian::where('draft_group_id', $request->old_draft_group_id)
                    ->where('parameter_uji_id', $paramUji->parameter_uji_id)
                    ->first();
            }

            if ($qch) {
                $qch->update($attributes);
            } else {
                $qch = QcHarian::create($attributes);
            }

            $savedCount++;

            if (!$isDraft && isset($mentah['barang_ids']) && is_array($mentah['barang_ids'])) {
                foreach ($mentah['barang_ids'] as $barangId) {
                    $qtyDipakai = isset($mentah['barang_jumlah'][$barangId]) ? (float) $mentah['barang_jumlah'][$barangId] : 0;
                    if ($qtyDipakai > 0) {
                        $barang = \App\Models\Barang::find($barangId);
                        if ($barang) {
                            $barang->pengeluaran += $qtyDipakai;
                            $barang->save();
                        }
                    }
                }
            }
            
            if (!$isDraft) {
                $results[] = strtoupper($pName) . ': ' . strtoupper($evalStatus);
                if ($evalStatus === 'outlier') {
                    $outlierParams[] = strtoupper($pName);
                }
            }
        } 

        if ($savedCount === 0) {
            return back()->with('error', 'Tidak ada parameter yang berhasil disimpan. Pastikan Anda mencentang minimal 1 parameter dan mengisi nilainya.');
        }

        if ($isDraft) {
            return redirect()->route('qc-harian.index')->with('success', "Draft berhasil disimpan ({$savedCount} parameter).");
        }

        if (count($outlierParams) > 0) {
            $paramString = implode(', ', $outlierParams);
            
            // Kirim notifikasi lonceng (sesuai sistem notifikasi temanmu)
            $pesanNotif = "⚠️ Outlier QC Harian: Ditemukan outlier pada parameter {$paramString} (Tanggal: " . \Carbon\Carbon::parse($request->tanggal_uji)->format('d/m/Y') . "). Silakan selesaikan investigasi.";
            $urlNotif = route('qc-harian.index');

            // Ambil semua Analis dan Koordinator Lab yang aktif
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

            activity()
                ->causedBy(auth()->user())
                ->event('warning')
                ->log("⚠️ Terdeteksi OUTLIER pada pengujian QC Harian batch {$activeBatch->kode_batch} untuk parameter: {$paramString}");

            return redirect()->route('qc-harian.index')
                ->with('error', "PERINGATAN! Ditemukan Outlier pada pengujian: {$paramString}. Silakan selesaikan investigasi.");
        }

        activity()
            ->causedBy(auth()->user())
            ->event('created')
            ->log("Menyelesaikan pengujian QC Harian batch {$activeBatch->kode_batch} ({$savedCount} parameter: Inlier/Diterima)");

        return redirect()->route('qc-harian.index')
            ->with('success', "Data Harian QC berhasil disimpan dan dievaluasi ({$savedCount} parameter). " . implode(' | ', $results));
    }

    public function editDraft($id)
    {
        $draft = QcHarian::findOrFail($id);
        if ($draft->status_pengujian !== 'draft' || !$draft->draft_group_id) {
            return redirect()->route('qc-harian.index')->with('error', 'Data tersebut bukan draft atau sudah diselesaikan.');
        }

        $activeBatch = SampelInhouse::where('status', 'aktif')->latest()->first();
        $parameters = $activeBatch->parameters()->where('status_parameter', 'stabil')->with('parameterUji')->get();
        $alatList = \App\Models\Alat::all();
        $personilList = \App\Models\Personil::all();
        $barangList = \App\Models\Barang::all();

        $draftGroup = QcHarian::where('draft_group_id', $draft->draft_group_id)->get();
        $serverDraft = [
            'draft_group_id' => $draft->draft_group_id,
            'tanggal_uji' => $draft->tanggal_uji,
            'nama_sampel' => $draftGroup->first()->data_mentah['nama_sampel_uji'] ?? '',
            'params' => []
        ];

        foreach ($draftGroup as $d) {
            $pid = $d->parameter_uji_id;
            $serverDraft['params'][$pid] = [
                'selected' => true,
                'd1' => $d->nilai_d1,
                'd2' => $d->nilai_d2,
                'mentah' => $d->data_mentah
            ];
        }

        return view('qc-harian.create', compact('activeBatch', 'parameters', 'alatList', 'personilList', 'barangList', 'serverDraft'));
    }

    public function destroyDraft($id)
    {
        $draft = QcHarian::findOrFail($id);
        if ($draft->draft_group_id) {
            QcHarian::where('draft_group_id', $draft->draft_group_id)->delete();
        } else {
            $draft->delete();
        }

        return redirect()->route('qc-harian.index')->with('success', 'Draft berhasil dihapus.');
    }

    public function chart($parameter_uji_id)
    {
        $activeBatch = SampelInhouse::where('status', 'aktif')->latest()->first();
        if (!$activeBatch) {
            return redirect()->route('qc-harian.index')->with('error', 'Tidak ada batch aktif.');
        }

        $paramUji = ParameterUji::findOrFail($parameter_uji_id);
        
        $logs = QcHarian::where('sampel_inhouse_id', $activeBatch->sampel_inhouse_id)
            ->where('parameter_uji_id', $parameter_uji_id)
            ->orderBy('tanggal_uji', 'asc')
            ->orderBy('created_at', 'asc')
            ->take(30)
            ->get();

        return view('qc-harian.chart', compact('activeBatch', 'paramUji', 'logs'));
    }

    public function investigasi($id)
    {
        $qc = QcHarian::with(['parameterUji', 'sampelInhouse'])->findOrFail($id);
        return view('qc-harian.investigasi', compact('qc'));
    }

    public function storeInvestigasi(Request $request, $id)
    {
        $request->validate([
            'akar_masalah' => 'required|string',
            'catatan_investigasi' => 'required|string|min:10',
            'tindakan_perbaikan' => 'required|string|min:10',
        ]);

        $qc = QcHarian::findOrFail($id);
        $qc->update([
            'akar_masalah' => $request->akar_masalah,
            'catatan_investigasi' => $request->catatan_investigasi,
            'tindakan_perbaikan' => $request->tindakan_perbaikan,
            'status_investigasi' => 'selesai_investigasi'
        ]);

        $paramName = $qc->parameterUji->nama_parameter ?? 'Parameter';
        activity()
            ->performedOn($qc)
            ->causedBy(auth()->user())
            ->event('updated')
            ->log("Menyelesaikan investigasi penyimpangan QC Harian parameter {$paramName} (Akar masalah: {$request->akar_masalah})");

        return redirect()->route('qc-harian.index')->with('success', 'Investigasi berhasil disimpan. Parameter telah dibuka untuk pengujian RETEST.');
    }

    public function printPdf(Request $request)
    {
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));
        
        $cetakRiwayat = $request->input('cetak_riwayat') == 1;
        $cetakChart = $request->input('cetak_chart') == 1;
        
        $paramType = $request->input('param_type', 'all');
        $paramIds = $request->input('param_ids', []);

        $activeBatch = SampelInhouse::where('status', 'aktif')->latest()->first();
        if (!$activeBatch) {
            return redirect()->route('qc-harian.index')->with('error', 'Tidak ada batch QC In-House yang aktif.');
        }

        $queryParam = $activeBatch->parameters()->where('status_parameter', 'stabil')->with('parameterUji');
        if ($paramType === 'specific' && !empty($paramIds)) {
            $queryParam->whereIn('parameter_uji_id', $paramIds);
        }
        $parameters = $queryParam->get();

        if ($parameters->isEmpty()) {
            return redirect()->route('qc-harian.index')->with('error', 'Tidak ada parameter yang dipilih atau tersedia.');
        }

        $reportData = [];
        foreach ($parameters as $param) {
            $paramModel = $param->parameterUji;
            
            $logs = QcHarian::where('sampel_inhouse_id', $activeBatch->sampel_inhouse_id)
                ->where('parameter_uji_id', $param->parameter_uji_id)
                ->whereMonth('tanggal_uji', $bulan)
                ->whereYear('tanggal_uji', $tahun)
                ->with('analis')
                ->orderBy('tanggal_uji', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();
                
            $reportData[] = [
                'parameter' => $paramModel,
                'mean' => (float)$paramModel->mean,
                'sd' => (float)$paramModel->sd,
                'logs' => $logs
            ];
        }

        $bulanName = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$bulan - 1];
        $periode = "$bulanName $tahun";

        return view('qc-harian.print-pdf', compact(
            'activeBatch', 
            'reportData', 
            'periode', 
            'cetakRiwayat', 
            'cetakChart'
        ));
    }
    public function cetakInvestigasiPdf($id)
    {
        $qc = QcHarian::with(['parameterUji', 'sampelInhouse', 'analis'])->findOrFail($id);

        if ($qc->status_investigasi !== 'selesai_investigasi') {
            return back()->with('error', 'Laporan investigasi CAPA untuk data ini belum diselesaikan.');
        }

        $pdf = Pdf::loadView('qc-harian.pdf_investigasi', compact('qc'));
        $fileName = 'CAPA_QC_Harian_' . strtoupper($qc->parameterUji->nama_parameter ?? 'PARAM') . '_' . $qc->tanggal_uji->format('Ymd') . '.pdf';

        return $pdf->download($fileName);
    }
    
}