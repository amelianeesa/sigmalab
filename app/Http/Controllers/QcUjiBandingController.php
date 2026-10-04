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
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class QcUjiBandingController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $search = $request->input('search');

        $programs = QcUjiBanding::with('parameters')
            ->when($search, function ($query, $search) {
                return $query->where('nama_program', 'like', "%{$search}%")
                             ->orWhere('penyelenggara', 'like', "%{$search}%")
                             ->orWhere('kode_sampel', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
            'kode_sampel' => 'required|string',
            'params' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $draftId = $request->input('draft_id');
            $dataToSave = [
                'nama_program' => $request->nama_program,
                'penyelenggara' => $request->penyelenggara,
                'tanggal_terima' => $request->tanggal_terima,
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

            $params = $request->input('params');
            $proximateAdl = $request->input('proximate_adl');

            if (!empty($params[4]['selected'])) {
                foreach ([5, 6] as $pid) {
                    if (!isset($params[$pid])) {
                        $params[$pid] = [];
                    }
                    $params[$pid]['selected'] = 1;
                    foreach (['mentah', 'ref_no', 'blnc_id', 'furnace_id', 'std_method'] as $k) {
                        if (!isset($params[$pid][$k]) && isset($params[4][$k])) {
                            $params[$pid][$k] = $params[4][$k];
                        }
                    }
                }
            }

            foreach ($params as $paramId => $data) {
                if (empty($data['selected'])) continue;

                $rows = $data['data'] ?? [];
                ksort($rows);

                $nilaiPerPengujian = [];
                foreach ($rows as $row) {
                    $d1  = $this->toFloat($row['d1'] ?? null);
                    $d2  = $this->toFloat($row['d2'] ?? null);
                    $db1 = $this->toFloat($row['db1'] ?? null);
                    $db2 = $this->toFloat($row['db2'] ?? null);

                    if ($db1 !== null && $db2 !== null) {
                        $nilaiPerPengujian[] = ($db1 + $db2) / 2;
                    } elseif ($d1 !== null && $d2 !== null) {
                        $nilaiPerPengujian[] = ($d1 + $d2) / 2;
                    }
                }

                $sum_d1 = 0;
                $sum_d2 = 0;
                $count_rows = count($rows);
                if ($count_rows > 0) {
                    foreach($rows as $r) {
                        $sum_d1 += $this->toFloat($r['d1'] ?? 0);
                        $sum_d2 += $this->toFloat($r['d2'] ?? 0);
                    }
                }
                $nilai_akhir = count($nilaiPerPengujian)
                    ? array_sum($nilaiPerPengujian) / count($nilaiPerPengujian)
                    : 0;

                QcUjiBandingParameter::create([
                    'qc_uji_banding_id' => $program->id,
                    'parameter_uji_id'  => $paramId,
                    'analis_id'  => $data['mentah']['personil_ids'][0] ?? null,
                    'alat_id'    => $data['mentah']['alat_ids'][0] ?? null,
                    'metode_uji' => $data['metode_uji'] ?? $data['std_method'] ?? null,
                    'uncertainty_lab' => $data['uncertainty_lab'] ?? null,

                    'nilai_d1'    => $count_rows > 0 ? ($sum_d1 / $count_rows) : null,
                    'nilai_d2'    => $count_rows > 0 ? ($sum_d2 / $count_rows) : null,
                    'nilai_akhir' => $nilai_akhir,

                    'data_mentah' => array_merge($data['mentah'] ?? [], [
                        'rows' => $rows,
                        'proximate_adl' => $proximateAdl ?? null,  
                        'meta' => Arr::only($data, [
                            'ref_no', 'blnc_id', 'time', 'furnace_id', 'std_method',
                            'indicate_t', 'calorimeter_id', 'metode_uji', 'uncertainty_lab',
                        ]),
                    ]),
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

    public function edit($id)
    {
        $qc = QcUjiBanding::with('parameters')->findOrFail($id);

        $draftData = [
            'nama_program' => $qc->nama_program,
            'penyelenggara' => $qc->penyelenggara,
            'kode_sampel' => $qc->kode_sampel,
            'tanggal_terima' => $qc->tanggal_terima ? $qc->tanggal_terima->format('Y-m-d') : null,
            'keterangan' => $qc->keterangan,
            'params' => [],
        ];

        foreach ($qc->parameters as $p) {
            $pid = $p->parameter_uji_id;
            $mentah = is_string($p->data_mentah) ? json_decode($p->data_mentah, true) : ($p->data_mentah ?? []);

            if (isset($mentah['proximate_adl'])) {
                $draftData['proximate_adl'] = $mentah['proximate_adl'];
            }
            
            $analis_data = [];
            if (!empty($mentah['personil_ids'])) {
                foreach ((array) $mentah['personil_ids'] as $personil_id) {
                    $peran = $mentah['personil_peran'][$personil_id] ?? 'Analis';
                    $analis_data[] = ['id' => $personil_id, 'peran' => $peran];
                }
            }

            $alat_ids = $mentah['alat_ids'] ?? [];

            $inputs = [];
            if (!empty($mentah['rows'])) {
                foreach ($mentah['rows'] as $idx => $row) {
                    foreach ($row as $colName => $colValue) {
                        
                        if ($colName === 'mentah' && is_array($colValue)) {
                            foreach ($colValue as $subColName => $subColValue) {
                                $inputName = "params[{$pid}][data][{$idx}][mentah][{$subColName}]";
                                $inputs[$inputName] = $subColValue;
                            }
                        } else {
                            $inputName = "params[{$pid}][data][{$idx}][{$colName}]";
                            $inputs[$inputName] = $colValue;
                        }
                    }
                }
            }

            $draftData['params'][$pid] = [
                'selected' => '1',
                'analis_data' => $analis_data,
                'alat_data' => $alat_ids, // <--- SUDAH BENAR
                'inputs' => $inputs,
            ];
            
            if (isset($mentah['meta'])) {
                foreach ($mentah['meta'] as $k => $v) {
                    $draftData['params'][$pid][$k] = $v;
                    $inputName = "params[{$pid}][{$k}]";
                    $draftData['params'][$pid]['inputs'][$inputName] = $v;
                }
            }
        }

        if (isset($draftData['params'][4])) {
            foreach ([5, 6] as $pidHN) {
                if (isset($draftData['params'][$pidHN]['inputs'])) {
                    $draftData['params'][4]['inputs'] = array_merge(
                        $draftData['params'][4]['inputs'], 
                        $draftData['params'][$pidHN]['inputs']
                    );
                }
            }
        }

        return view('qc-uji-banding.create', [
            'is_edit' => true,
            'qc' => $qc,
            'draftData' => $draftData,
            'barangList' => \App\Models\Barang::all(),
            'allParameters' => \App\Models\ParameterUji::all(),
            'personilList' => \App\Models\Personil::all(),
            'alatList' => \App\Models\Alat::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_program' => 'required|string',
            'penyelenggara' => 'required|string',
            'tanggal_terima' => 'required|date',
            'kode_sampel' => 'required|string',
            'params' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $qc = QcUjiBanding::findOrFail($id);
            $data = $request->all();

            $qc->update([
                'nama_program' => $data['nama_program'],
                'penyelenggara' => $data['penyelenggara'],
                'kode_sampel' => $data['kode_sampel'],
                'tanggal_terima' => $data['tanggal_terima'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            $qc->parameters()->delete();

            $params = $request->input('params');
            $proximateAdl = $request->input('proximate_adl');

            if (!empty($params[4]['selected'])) {
                foreach ([5, 6] as $pid) {
                    if (!isset($params[$pid])) {
                        $params[$pid] = [];
                    }
                    $params[$pid]['selected'] = 1;
                    foreach (['mentah', 'ref_no', 'blnc_id', 'furnace_id', 'std_method'] as $k) {
                        if (!isset($params[$pid][$k]) && isset($params[4][$k])) {
                            $params[$pid][$k] = $params[4][$k];
                        }
                    }
                }
            }

            foreach ($params as $paramId => $dataParams) {
                if (empty($dataParams['selected'])) continue;

                $rows = $dataParams['data'] ?? [];
                ksort($rows);

                $nilaiPerPengujian = [];
                foreach ($rows as $row) {
                    $d1  = $this->toFloat($row['d1'] ?? null);
                    $d2  = $this->toFloat($row['d2'] ?? null);
                    $db1 = $this->toFloat($row['db1'] ?? null);
                    $db2 = $this->toFloat($row['db2'] ?? null);

                    if ($db1 !== null && $db2 !== null) {
                        $nilaiPerPengujian[] = ($db1 + $db2) / 2;
                    } elseif ($d1 !== null && $d2 !== null) {
                        $nilaiPerPengujian[] = ($d1 + $d2) / 2;
                    }
                }

                $sum_d1 = 0;
                $sum_d2 = 0;
                $count_rows = count($rows);
                if ($count_rows > 0) {
                    foreach($rows as $r) {
                        $sum_d1 += $this->toFloat($r['d1'] ?? 0);
                        $sum_d2 += $this->toFloat($r['d2'] ?? 0);
                    }
                }
                
                $nilai_akhir = count($nilaiPerPengujian)
                    ? array_sum($nilaiPerPengujian) / count($nilaiPerPengujian)
                    : 0;

                QcUjiBandingParameter::create([
                    'qc_uji_banding_id' => $qc->id,
                    'parameter_uji_id'  => $paramId,
                    'analis_id'  => $dataParams['mentah']['personil_ids'][0] ?? null,
                    'alat_id'    => $dataParams['mentah']['alat_ids'][0] ?? null,
                    'metode_uji' => $dataParams['metode_uji'] ?? $dataParams['std_method'] ?? null,
                    'uncertainty_lab' => $dataParams['uncertainty_lab'] ?? null,

                    'nilai_d1'    => $count_rows > 0 ? ($sum_d1 / $count_rows) : null,
                    'nilai_d2'    => $count_rows > 0 ? ($sum_d2 / $count_rows) : null,
                    'nilai_akhir' => $nilai_akhir,

                    'data_mentah' => array_merge($dataParams['mentah'] ?? [], [
                        'rows' => $rows,
                        'proximate_adl' => $proximateAdl ?? null,
                        'meta' => Arr::only($dataParams, [
                            'ref_no', 'blnc_id', 'time', 'furnace_id', 'std_method',
                            'indicate_t', 'calorimeter_id', 'metode_uji', 'uncertainty_lab',
                        ]),
                    ]),
                    'status_evaluasi' => 'menunggu',
                ]);
            }

            DB::commit();
            return redirect()->route('qc-uji-banding.show', $qc->id)->with('success', 'Data Uji Banding berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat update: ' . $e->getMessage())->withInput();
        }
    }

    public function printPdf($id)
    {
        $program = QcUjiBanding::with(['parameters.parameterUji', 'parameters.analis'])->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('qc-uji-banding.print.pdf', compact('program'));

        $pdf->setPaper('A4', 'landscape'); 
        
        return $pdf->stream('Laporan_Uji_Banding_' . $program->kode_sampel . '.pdf');
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

    public function evaluasiForm(Request $request, $id)
    {
        $program = QcUjiBanding::with(['parameters.parameterUji'])->findOrFail($id);
        
        $selectedIds = $request->input('p', []);
        
        if (empty($selectedIds)) {
            return back()->with('error', 'Silakan centang minimal satu parameter untuk dievaluasi.');
        }

        $program->setRelation('parameters', $program->parameters->whereIn('id', $selectedIds));

        return view('qc-uji-banding.evaluasi-vendor', compact('program'));
    }

    public function evaluasiStore(Request $request, $id)
    {
        $program = QcUjiBanding::findOrFail($id);

        $request->validate([
            'params' => 'required|array',
            'params.*.lab_value' => 'nullable|numeric',
            'params.*.target_vendor' => 'nullable|numeric',
            'params.*.sdpa' => 'nullable|numeric',
        ]);

        foreach ($request->params as $paramId => $data) {
            $labValue = isset($data['lab_value']) && $data['lab_value'] !== '' ? floatval($data['lab_value']) : null;
            $assignedValue = $data['target_vendor'] ?? null;
            $sdpa = $data['sdpa'] ?? null;

            $parameter = QcUjiBandingParameter::where('qc_uji_banding_id', $id)->find($paramId);
            if (!$parameter) continue;

            if ($labValue !== null) {
                $parameter->nilai_akhir = $labValue;
            }

            if ($assignedValue === null || $assignedValue === '' || $sdpa === null || $sdpa === '' || floatval($sdpa) == 0) {
                $parameter->save(); // Simpan lab_value meski belum evaluasi
                continue; 
            }

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
                'nilai_akhir' => $parameter->nilai_akhir,
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

    private function toFloat($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        
        $value = str_replace(',', '.', $value);
        
        return floatval($value);
    }
}
