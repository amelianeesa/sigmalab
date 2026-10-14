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
use Barryvdh\DomPDF\Facade\Pdf;

class QcCrmController extends Controller
{
    public function index()
    {
        $kegiatanList = QcCrm::with(['crmKatalog', 'parameterUji', 'analis'])
            ->orderBy('tanggal_uji', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $katalogs = CrmKatalog::with(['sertifikats.parameterUji', 'verifikasiTeknis.parameterUji', 'verifikasiTeknis.analis', 'verifikasiTeknis.personils'])->orderBy('created_at', 'desc')->get();
        
        return view('qc-crm.index', compact('kegiatanList', 'katalogs'));
    }

    public function create(\Illuminate\Http\Request $request)
    {
        $activeCrms = CrmKatalog::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('tanggal_expired')
                      ->orWhereDate('tanggal_expired', '>=', now());
            })
        ->orderBy('nomor_lot')
        ->get();
        $personilList = Personil::orderBy('nama')->get();
        $alatList = Alat::where('kondisi_barang', 'baik')->orderBy('nama_alat')->get();
        $barangList = Barang::orderBy('nama_barang')->get();
        $allParameters = \App\Models\ParameterUji::where('status_aktif', true)->orderBy('nama_parameter')->get();

        $serverDraft = null;
        
        if (old('params')) {
            $serverDraft = [
                'crm_katalog_id' => old('crm_katalog_id'),
                'tanggal_uji' => old('tanggal_uji'),
                'no_lembar_kerja' => old('no_lembar_kerja'),
                'params' => []
            ];

            foreach (old('params', []) as $pid => $data) {
                if (!isset($data['selected'])) continue;

                $inputs = [];
                if (isset($data['d1'])) $inputs['in-d1'] = $data['d1'];
                if (isset($data['d2'])) $inputs['in-d2'] = $data['d2'];
                if (isset($data['db1'])) $inputs['in-db-1'] = $data['db1'];
                if (isset($data['db2'])) $inputs['in-db-2'] = $data['db2'];
                if (isset($data['catatan'])) $inputs['in-catatan'] = $data['catatan'];

                if (isset($data['mentah']) && is_array($data['mentah'])) {
                    foreach ($data['mentah'] as $key => $val) {
                        $inputs['in-' . str_replace('_', '-', $key)] = $val;
                    }
                }

                $serverDraft['params'][$pid] = [
                    'selected' => true,
                    'inputs' => $inputs,
                    'resource' => \Illuminate\Support\Arr::only($data, ['personil_ids','personil_peran','alat_ids','barang_ids','barang_jumlah']),
                ];
            }
        } 
        // 2. JIKA BUKAN ERROR, TAPI LANJUTKAN DRAFT SEPERTI BIASA
        elseif ($request->has('resume') && $request->has('crm_id')) {
            $draftRows = QcCrm::where('crm_katalog_id', $request->crm_id)
                              ->where('status_evaluasi', 'draft')
                              ->get();
                              
            if ($draftRows->isNotEmpty()) {
                $first = $draftRows->first();
                $serverDraft = [
                    'crm_katalog_id' => $first->crm_katalog_id,
                    'tanggal_uji' => $first->tanggal_uji ? $first->tanggal_uji->format('Y-m-d') : null,
                    'analis_id' => $first->analis_id,
                    'no_lembar_kerja' => $first->no_lembar_kerja,
                    'params' => []
                ];
                
                foreach ($draftRows as $row) {
                    $mentah = is_string($row->data_mentah) ? json_decode($row->data_mentah, true) : ($row->data_mentah ?? []);
                    
                    $inputs = [];
                    foreach ($mentah as $key => $val) {
                        // Sesuaikan nama field di database dengan class HTML di frontend
                        $classKey = 'in-' . str_replace('_', '-', $key);
                        $inputs[$classKey] = $val;
                    }
                    
                    $inputs['in-d1'] = $row->nilai_d1;
                    $inputs['in-d2'] = $row->nilai_d2;
                    $inputs['in-db-1'] = $row->nilai_db_1;
                    $inputs['in-db-2'] = $row->nilai_db_2;

                    $inputs['in-analis-id'] = $row->analis_id;


                    $serverDraft['params'][$row->parameter_uji_id] = [
                        'selected' => true,
                        'inputs' => $inputs,
                        'resource' => \Illuminate\Support\Arr::only($mentah, ['personil_ids','personil_peran','alat_ids','barang_ids','barang_jumlah']),
                    ];
                }
            }
        }

        $recentLogs = collect();
        $oldLembarKerja = old('no_lembar_kerja');
        $oldTanggal = old('tanggal_uji');

        if ($oldLembarKerja && $oldTanggal) {
            $recentLogs = \App\Models\QcCrm::with(['parameterUji', 'crmKatalog'])
                ->where('no_lembar_kerja', $oldLembarKerja)
                ->where('tanggal_uji', $oldTanggal)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('qc-crm.create', compact('activeCrms', 'personilList', 'alatList', 'barangList', 'allParameters', 'serverDraft', 'recentLogs'));
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
        $isDraft = $request->input('action') === 'draft';

            $rules = [
            'crm_katalog_id' => 'required|exists:crm_katalog,id',
            'params' => 'required|array',
        ];

        if (!$isDraft) {
            $rules['tanggal_uji'] = 'required|date';
            $rules['no_lembar_kerja'] = 'required|string|max:255'; 
        }

        $messages = [
            'no_lembar_kerja.required' => 'Nomor Lembar Kerja tidak boleh kosong.',
            'tanggal_uji.required'     => 'Tanggal Uji tidak boleh kosong.',
            'crm_katalog_id.required'  => 'Anda belum memilih Botol CRM dari atas.',
            'params.required'          => 'Anda harus mencentang dan mengisi minimal satu parameter uji.'
        ];

        $request->validate($rules, $messages);
        
        $katalogId = $request->crm_katalog_id;
        $tanggalUji = $request->tanggal_uji;
        
        $katalog = CrmKatalog::with('sertifikats.parameterUji')->findOrFail($katalogId);
        $sertifikatMap = $katalog->sertifikats->keyBy('parameter_uji_id');

        DB::beginTransaction();
        try {
            $outlierParams = [];
            foreach ($request->params as $paramId => $data) {
                if (!isset($data['selected'])) continue;
                if (!isset($sertifikatMap[$paramId])) continue;
                
                // Analis utama = personil pertama yang dipilih di modal
                $personilIds = $data['personil_ids'] ?? [];
                $analisIdPerParam = $personilIds[0] ?? null;

                if (!$isDraft && empty($analisIdPerParam)) {
                    throw new \Exception("Analis untuk parameter terpilih belum diatur (klik 'Atur Analis, Alat & Bahan')!");
                }

                // Simpan alat, bahan, dan peran bersama data mentah
                $mentah = $data['mentah'] ?? [];
                $mentah['personil_ids']   = $personilIds;
                $mentah['personil_peran'] = $data['personil_peran'] ?? [];
                $mentah['alat_ids']       = $data['alat_ids'] ?? [];
                $mentah['barang_ids']     = $data['barang_ids'] ?? [];
                $mentah['barang_jumlah']  = $data['barang_jumlah'] ?? [];
                
                $sertifikat = $sertifikatMap[$paramId];
                
                $nilai_d1 = isset($data['d1']) ? floatval($data['d1']) : null;
                $nilai_d2 = isset($data['d2']) ? floatval($data['d2']) : null;
                $nilai_db_1 = isset($data['db1']) ? floatval($data['db1']) : null;
                $nilai_db_2 = isset($data['db2']) ? floatval($data['db2']) : null;
                
                if ($nilai_db_1 !== null && $nilai_db_2 !== null) {
                    $nilai_akhir = ($nilai_db_1 + $nilai_db_2) / 2;
                } else if ($nilai_d1 !== null && $nilai_d2 !== null) {
                    $nilai_akhir = ($nilai_d1 + $nilai_d2) / 2;
                } else {
                    $nilai_akhir = null; 
                }
                
                $batas_bawah = $sertifikat->cert_value - $sertifikat->cert_u;
                $batas_atas = $sertifikat->cert_value + $sertifikat->cert_u;
                
                if ($isDraft) {
                    $status = 'draft';
                } else {
                    $status = ($nilai_akhir >= $batas_bawah && $nilai_akhir <= $batas_atas) ? 'inlier' : 'outlier';
                    if ($status === 'outlier') {
                        $pName = $sertifikat->parameterUji->nama_parameter ?? ('ID ' . $paramId);
                        $outlierParams[] = $pName;
                    }
                }
                
                $draftLama = QcCrm::where('crm_katalog_id', $katalogId)
                                  ->where('parameter_uji_id', $paramId)
                                  ->where('status_evaluasi', 'draft')
                                  ->first();

                $dataSimpan = [
                    'crm_katalog_id' => $katalogId,
                    'parameter_uji_id' => $paramId,
                    'tanggal_uji' => $tanggalUji,
                    'no_lembar_kerja' => $request->no_lembar_kerja,
                    'analis_id' => $analisIdPerParam, 
                    'nilai_d1' => $nilai_d1,
                    'nilai_d2' => $nilai_d2,
                    'nilai_db_1' => $nilai_db_1,
                    'nilai_db_2' => $nilai_db_2,
                    'nilai_akhir' => $nilai_akhir,
                    'cert_value' => $sertifikat->cert_value,
                    'cert_u' => $sertifikat->cert_u,
                    'status_evaluasi' => $status,
                    'status_investigasi' => ($status === 'outlier') ? 'menunggu_investigasi' : 'aman',
                    'data_mentah' => $mentah,
                    'catatan' => $data['catatan'] ?? null,
                ];

                if ($draftLama) {
                    $draftLama->update($dataSimpan);
                } else {
                    QcCrm::create($dataSimpan);
                }
            }

            DB::commit();

            if (!$isDraft && count($outlierParams) > 0) {
                $paramString = implode(', ', array_unique($outlierParams));
                $pesanNotif = "⚠️ Outlier QC CRM: Ditemukan outlier pada parameter {$paramString} (Produk: {$katalog->nama_produk}, Lot: {$katalog->nomor_lot}). Silakan lakukan investigasi.";
                $urlNotif = route('qc-crm.index', ['tab' => 'harian']);
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

            // --- INI LOGIKA SMART STICKY FORM-NYA ---
            if ($request->input('action') === 'save_and_add') {
                return redirect()->route('qc-crm.create')
                                 ->withInput($request->only(['tanggal_uji', 'no_lembar_kerja']))
                                 ->with('success', 'Pengujian botol berhasil disimpan! Silakan lanjut pilih botol berikutnya.');
            }
            

            if ($isDraft) {
                return redirect()->route('qc-crm.index')->with('success', 'Draft Pengujian Harian berhasil disimpan.');
            }

            
            $firstOutlier = QcCrm::where('crm_katalog_id', $katalog->id)
                ->where('tanggal_uji', $request->tanggal_uji)
                ->where('status_evaluasi', 'outlier')
                ->where('status_investigasi', 'menunggu_investigasi')
                ->latest('id')
                ->first();

            if ($firstOutlier) {
                return redirect()->route('qc-crm.index', ['tab' => 'harian'])
                    ->with('error', 'PERINGATAN! Ditemukan data OUTLIER pada pengujian CRM. Silakan klik tombol Investigasi (CAPA) pada tabel di bawah.');
            }
            
            return redirect()->route('qc-crm.index')->with('success', 'Data pengujian CRM berhasil diselesaikan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function chartData(Request $request)
    {
        $katalogId = $request->input('crm_katalog_id');
        $paramId = $request->input('parameter_uji_id');

        if (!$katalogId || !$paramId) {
            return response()->json([]);
        }

        // Ambil data pengujian harian (hanya yang FINAL, bukan draft)
        $logs = QcCrm::with('analis') // <--- INI KUNCI UTAMANYA
                      ->where('crm_katalog_id', $katalogId)
                      ->where('parameter_uji_id', $paramId)
                      ->whereIn('status_evaluasi', ['inlier', 'outlier'])
                      ->orderBy('tanggal_uji', 'asc')
                      ->orderBy('created_at', 'asc')
                      ->get();

        // Ambil data sertifikat (True Value & Uncertainty)
        $sertifikat = CrmSertifikat::where('crm_katalog_id', $katalogId)
                                   ->where('parameter_uji_id', $paramId)
                                   ->first();

        if (!$sertifikat) {
            return response()->json([]);
        }

        // Deteksi Trend: 7 titik berturut-turut di satu sisi True Value
        $trendWarning = null;
        $trendIndices = [];
        if ($logs->count() >= 7) {
            $values = $logs->pluck('nilai_akhir')->toArray();
            $certVal = $sertifikat->cert_value;

            for ($i = 0; $i <= count($values) - 7; $i++) {
                $slice = array_slice($values, $i, 7);
                $allAbove = true;
                $allBelow = true;

                foreach ($slice as $v) {
                    if ($v <= $certVal) $allAbove = false;
                    if ($v >= $certVal) $allBelow = false;
                }

                if ($allAbove || $allBelow) {
                    $trendWarning = $allAbove ? 'atas' : 'bawah';
                    $trendIndices = range($i, $i + 6);
                    // Terus cari sampai akhir agar dapat range terluas
                }
            }
        }

        return response()->json([
            'logs' => $logs,
            'cert_value' => (float)$sertifikat->cert_value,
            'cert_u' => (float)$sertifikat->cert_u,
            'trend_warning' => $trendWarning,
            'trend_indices' => $trendIndices,
        ]);
    }

    public function edit($id)
    {
        $log = \App\Models\QcCrm::findOrFail($id);
    
        $log->status_evaluasi = 'draft';
        $log->save();

        return redirect()->route('qc-crm.create', [
            'resume' => 1, 
            'crm_id' => $log->crm_katalog_id
        ])->withInput([
            'tanggal_uji' => $log->tanggal_uji ? $log->tanggal_uji->format('Y-m-d') : null,
            'no_lembar_kerja' => $log->no_lembar_kerja
        ])->with('success', 'Mode Edit diaktifkan. Silakan perbaiki angka pada form di bawah lalu simpan kembali.');
    }

        public function investigasi($id)
    {
        // Ambil data pengujian beserta relasinya
        $qc = \App\Models\QcCrm::with(['parameterUji', 'crmKatalog'])->findOrFail($id);
        
        // Cegah akses jika datanya bukan outlier
        if ($qc->status_evaluasi !== 'outlier') {
            return redirect()->route('qc-crm.index')->with('error', 'Hanya data outlier yang memerlukan investigasi.');
        }

        return view('qc-crm.investigasi', compact('qc'));
    }

    public function storeInvestigasi(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'akar_masalah' => 'required|string',
            'catatan_investigasi' => 'required|string|min:10',
            'tindakan_perbaikan' => 'required|string|min:10',
        ]);

        $qc = \App\Models\QcCrm::findOrFail($id);
        $qc->update([
            'akar_masalah' => $request->akar_masalah,
            'catatan_investigasi' => $request->catatan_investigasi,
            'tindakan_perbaikan' => $request->tindakan_perbaikan,
            'status_investigasi' => 'selesai_investigasi'
        ]);

        return redirect()->route('qc-crm.index', ['tab' => 'harian'])
            ->with('success', 'Laporan investigasi & CAPA pengujian CRM berhasil disimpan.');
    }
    
    public function cetakInvestigasiPdf($id)
    {
        $qc = \App\Models\QcCrm::with(['parameterUji', 'crmKatalog', 'analis'])->findOrFail($id);

        if ($qc->status_investigasi !== 'selesai_investigasi') {
            return back()->with('error', 'Laporan investigasi CAPA belum diselesaikan.');
        }

        $pdf = Pdf::loadView('qc-crm.pdf_investigasi', compact('qc'));
        $fileName = 'CAPA_CRM_' . ($qc->crmKatalog->nomor_lot ?? 'LOT') . '_' . strtoupper($qc->parameterUji->nama_parameter ?? 'PARAM') . '_' . $qc->tanggal_uji->format('Ymd') . '.pdf';

        return $pdf->download($fileName);
    }

}


