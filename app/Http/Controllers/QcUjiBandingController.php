<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QcUjiBanding;
use App\Models\QcUjiBandingParameter;
use App\Models\ParameterUji;
use App\Models\Personil;
use App\Models\Alat;
use App\Models\Barang;
use App\Models\AftKalibrasi;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class QcUjiBandingController extends Controller
{
    public function index()
    {
        $programs = QcUjiBanding::with('parameters')->orderBy('created_at', 'desc')->get();
        return view('qc-uji-banding.index', compact('programs'));
    }

    public function create(Request $request)
    {
        $allParameters = ParameterUji::where('status_aktif', true)->orderBy('nama_parameter')->get();
    
        $alatList = Alat::all();
        $personilList = Personil::orderBy('nama')->get();
        $barangList = Barang::where('saldo_akhir', '>', 0)->get();

        
        $draftId = $request->query('draft_id');
        $draftData = null;
        if ($draftId) {
            $draft = QcUjiBanding::findOrFail($draftId);
            if ($draft->status === 'draft') {
                $draftData = $draft->draft_data;
            }
        }
        
        return view('qc-uji-banding.create', compact('allParameters', 'alatList', 'personilList', 'barangList', 'draftId', 'draftData'));
    }

    
    public function storeDraft(Request $request)
    {
        $id = $request->input('draft_id');
        $draftData = $request->except(['_token', 'draft_id']);
        
        $dataToSave = [
            'status' => 'draft',
            'draft_data' => $draftData,
            'nama_program' => $draftData['nama_program'] ?? 'Draft - ' . now()->format('Y-m-d H:i'),
            'penyelenggara' => $draftData['penyelenggara'] ?? null,
            'tanggal_terima' => !empty($draftData['tanggal_terima']) ? $draftData['tanggal_terima'] : null,
            'tanggal_uji' => !empty($draftData['tanggal_uji']) ? $draftData['tanggal_uji'] : null,
            'kode_sampel' => $draftData['kode_sampel'] ?? null,
            'keterangan' => $draftData['keterangan'] ?? null,
        ];

        if ($id) {
            $draft = QcUjiBanding::findOrFail($id);
            $draft->update($dataToSave);
        } else {
            $draft = QcUjiBanding::create($dataToSave);
        }

        return response()->json(['success' => true, 'draft_id' => $draft->id, 'message' => 'Draft berhasil disimpan.']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_program' => 'required|string',
            'penyelenggara' => 'required|string',
            'tanggal_terima' => 'required|date',
            'tanggal_uji' => 'required|date',
            'kode_sampel' => 'required|string',
            'analis_id' => 'required|exists:personil,id',
            'params' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $draftId = $request->input('draft_id');
            $dataToSave = [
                'nama_program' => $request->nama_program,
                'penyelenggara' => $request->penyelenggara,
                'tanggal_terima' => $request->tanggal_terima,
                'tanggal_uji' => $request->tanggal_uji,
                'kode_sampel' => $request->kode_sampel,
                'keterangan' => $request->keterangan,
                'status' => 'completed',
            ];
            
            if ($draftId) {
                $program = QcUjiBanding::findOrFail($draftId);
                $program->update($dataToSave);
            } else {
                $program = QcUjiBanding::create($dataToSave);
            }

            foreach ($request->params as $paramId => $data) {
                if (empty($data['selected'])) continue;
                
                $nilai_d1 = isset($data['d1']) ? floatval($data['d1']) : null;
                $nilai_d2 = isset($data['d2']) ? floatval($data['d2']) : null;
                $nilai_db_1 = isset($data['db1']) ? floatval($data['db1']) : null;
                $nilai_db_2 = isset($data['db2']) ? floatval($data['db2']) : null;
                
                if ($nilai_db_1 !== null && $nilai_db_2 !== null) {
                    $nilai_akhir = ($nilai_db_1 + $nilai_db_2) / 2;
                } else if ($nilai_d1 !== null && $nilai_d2 !== null) {
                    $nilai_akhir = ($nilai_d1 + $nilai_d2) / 2;
                } else {
                    $nilai_akhir = 0;
                }

                QcUjiBandingParameter::create([
                    'qc_uji_banding_id' => $program->id,
                    'parameter_uji_id' => $paramId,
                    'analis_id' => $request->analis_id,
                    'alat_id' => $data['alat_id'] ?? null,
                    'metode_uji' => $data['metode_uji'] ?? null,
                    'uncertainty_lab' => $data['uncertainty_lab'] ?? null,

                    'nilai_d1' => $nilai_d1,
                    'nilai_d2' => $nilai_d2,
                    'nilai_akhir' => $nilai_akhir,
                    'data_mentah' => $data['mentah'] ?? null,
                    'status_evaluasi' => 'menunggu',
                ]);
            }

            DB::commit();
            return redirect()->route('qc-uji-banding.show', $program->id)->with('success', 'Data Uji Banding berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $program = QcUjiBanding::with(['parameters.parameterUji', 'parameters.analis'])->findOrFail($id);
        return view('qc-uji-banding.show', compact('program'));
    }

    
    public function printPdf($id)
    {
        $program = QcUjiBanding::with(['parameters.parameterUji', 'parameters.analis'])->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('qc-uji-banding.print.pdf', compact('program'));
        // Set paper A4 landscape or portrait depending on need. Let's do portrait
        $pdf->setPaper('A4', 'landscape'); 
        
        return $pdf->stream('Laporan_Uji_Banding_' . $program->kode_sampel . '.pdf');
    }

    public function editEvaluasi($id, $param_id)
    {
        $program = QcUjiBanding::findOrFail($id);
        $parameter = QcUjiBandingParameter::with('parameterUji')->where('qc_uji_banding_id', $id)->findOrFail($param_id);
        return view('qc-uji-banding.evaluasi', compact('program', 'parameter'));
    }

    public function updateEvaluasi(Request $request, $id, $param_id)
    {
        $request->validate([
            'target_vendor' => 'nullable|numeric',
            'z_score' => 'nullable|numeric',
            'status_evaluasi' => 'required|in:inlier,warning,outlier',
        ]);

        $parameter = QcUjiBandingParameter::where('qc_uji_banding_id', $id)->findOrFail($param_id);
        
        $statusInvestigasi = $parameter->status_investigasi;
        if ($request->status_evaluasi === 'outlier' && $statusInvestigasi === 'aman') {
            $statusInvestigasi = 'menunggu_investigasi';
        } elseif ($request->status_evaluasi !== 'outlier') {
            $statusInvestigasi = 'aman';
        }

        $parameter->update([
            'target_vendor' => $request->target_vendor,
            'z_score' => $request->z_score,
            'status_evaluasi' => $request->status_evaluasi,
            'status_investigasi' => $statusInvestigasi,
        ]);

        return redirect()->route('qc-uji-banding.show', $id)->with('success', 'Evaluasi berhasil disimpan.');
    }

    public function investigasi($id, $param_id)
    {
        $program = QcUjiBanding::findOrFail($id);
        $parameter = QcUjiBandingParameter::with(['parameterUji', 'analis'])->where('qc_uji_banding_id', $id)->findOrFail($param_id);
        
        if ($parameter->status_evaluasi !== 'outlier') {
            return redirect()->route('qc-uji-banding.show', $id)->with('error', 'Hanya parameter berstatus outlier yang memerlukan investigasi.');
        }

        return view('qc-uji-banding.investigasi', compact('program', 'parameter'));
    }

    public function storeInvestigasi(Request $request, $id, $param_id)
    {
        $request->validate([
            'akar_masalah' => 'required|string',
            'tindakan_perbaikan' => 'required|string',
            'tindakan_pencegahan' => 'nullable|string',
        ]);

        $parameter = QcUjiBandingParameter::where('qc_uji_banding_id', $id)->findOrFail($param_id);
        
        $parameter->update([
            'akar_masalah' => $request->akar_masalah,
            'tindakan_perbaikan' => $request->tindakan_perbaikan,
            'tindakan_pencegahan' => $request->tindakan_pencegahan,
            'status_investigasi' => 'selesai_investigasi',
        ]);

        return redirect()->route('qc-uji-banding.show', $id)->with('success', 'Lembar Ketidaksesuaian berhasil disimpan.');
    }

    public function cetakLksWord($id, $param_id)
    {
        $program = QcUjiBanding::findOrFail($id);
        $parameter = QcUjiBandingParameter::with(['parameterUji', 'analis'])->where('qc_uji_banding_id', $id)->findOrFail($param_id);
        
        // Buat instance PhpWord
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16, 'allCaps' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTitle('LEMBAR KETIDAKSESUAIAN (LKS) / CAPA', 1);
        $section->addTextBreak(1);

        $section->addText("Nama Program : " . $program->nama_program, ['bold' => true]);
        $section->addText("Penyelenggara: " . $program->penyelenggara);
        $section->addText("Kode Sampel  : " . $program->kode_sampel);
        $section->addText("Parameter Uji: " . $parameter->parameterUji->nama_parameter);
        $section->addText("Analis       : " . ($parameter->analis->nama ?? '-'));
        $section->addText("Nilai Lab    : " . $parameter->nilai_akhir);
        $section->addText("Z-Score      : " . $parameter->z_score);
        $section->addTextBreak(1);

        $section->addText("Akar Masalah:", ['bold' => true, 'underline' => 'single']);
        $section->addText($parameter->akar_masalah);
        $section->addTextBreak(1);

        $section->addText("Tindakan Perbaikan:", ['bold' => true, 'underline' => 'single']);
        $section->addText($parameter->tindakan_perbaikan);
        $section->addTextBreak(1);
        
        $section->addText("Tindakan Pencegahan:", ['bold' => true, 'underline' => 'single']);
        $section->addText($parameter->tindakan_pencegahan ?? '-');

        $fileName = 'LKS_' . str_replace(' ', '_', $program->nama_program) . '_' . $parameter->parameterUji->nama_parameter . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'phpword');
        
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
    
    public function cetakLksPdf($id, $param_id)
    {
        $program = QcUjiBanding::findOrFail($id);
        $parameter = QcUjiBandingParameter::with(['parameterUji', 'analis'])->where('qc_uji_banding_id', $id)->findOrFail($param_id);
        
        $pdf = Pdf::loadView('qc-uji-banding.pdf_lks', compact('program', 'parameter'));
        
        $fileName = 'LKS_' . str_replace(' ', '_', $program->nama_program) . '_' . $parameter->parameterUji->nama_parameter . '.pdf';
        return $pdf->download($fileName);
    }

    // ========== AFT KALIBRASI ==========

    public function aftKalibrasiStore(Request $request)
    {
        $request->validate([
            'nama_kalibrasi' => 'nullable|string|max:255',
            'tanggal_kalibrasi' => 'required|date',
            'data_points' => 'required|array|min:2',
            'data_points.*.eq_sett' => 'required|numeric',
            'data_points.*.std_read' => 'required|numeric',
            'data_points.*.correction' => 'required|numeric',
        ]);

        // Deactivate all existing
        AftKalibrasi::where('is_active', true)->update(['is_active' => false]);

        $kalibrasi = AftKalibrasi::create([
            'nama_kalibrasi' => $request->nama_kalibrasi ?: 'Kalibrasi ' . now()->format('d M Y'),
            'tanggal_kalibrasi' => $request->tanggal_kalibrasi,
            'data_points' => $request->data_points,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'data' => $kalibrasi]);
    }

    public function aftKalibrasiList()
    {
        $list = AftKalibrasi::orderBy('created_at', 'desc')->get();
        return response()->json($list);
    }

    public function aftKalibrasiShow($id)
    {
        $kalibrasi = AftKalibrasi::findOrFail($id);
        return response()->json($kalibrasi);
    }

    public function evaluasiForm($id)
    {
        $program = QcUjiBanding::with(['parameters.parameterUji'])->findOrFail($id);
        return view('qc-uji-banding.evaluasi-vendor', compact('program'));
    }

    public function evaluasiStore(Request $request, $id)
    {
        $program = QcUjiBanding::findOrFail($id);

        $request->validate([
            'params' => 'required|array',
            'params.*.target_vendor' => 'nullable|numeric',
            'params.*.sdpa' => 'nullable|numeric',
        ]);

        foreach ($request->params as $paramId => $data) {
            $assignedValue = $data['target_vendor'] ?? null;
            $sdpa = $data['sdpa'] ?? null;

            if ($assignedValue === null || $assignedValue === '' || $sdpa === null || $sdpa === '' || floatval($sdpa) == 0) {
                continue; // belum lengkap, lewati — jangan hapus data yang sudah ada
            }

            $parameter = QcUjiBandingParameter::where('qc_uji_banding_id', $id)->find($paramId);
            if (!$parameter) continue;

            $assignedValue = floatval($assignedValue);
            $sdpa = floatval($sdpa);
            $zScore = ($parameter->nilai_akhir - $assignedValue) / $sdpa;
            $absZ = abs($zScore);

            $status = $absZ <= 2 ? 'inlier' : ($absZ < 3 ? 'warning' : 'outlier');

            $statusInvestigasi = $parameter->status_investigasi;
            if ($status === 'outlier' && $statusInvestigasi === 'aman') {
                $statusInvestigasi = 'menunggu_investigasi';
            } elseif ($status !== 'outlier') {
                $statusInvestigasi = 'aman';
            }

            $parameter->update([
                'target_vendor' => $assignedValue,
                'sdpa' => $sdpa,
                'z_score' => round($zScore, 2),
                'status_evaluasi' => $status,
                'status_investigasi' => $statusInvestigasi,
            ]);
        }

        return redirect()->route('qc-uji-banding.ringkasan', $id)->with('success', 'Hasil evaluasi vendor berhasil disimpan.');
    }

    public function ringkasanUnjukKerja($id)
    {
        $program = QcUjiBanding::with(['parameters.parameterUji'])->findOrFail($id);
        return view('qc-uji-banding.ringkasan-unjuk-kerja', compact('program'));
    }
}
