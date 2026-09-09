<?php

namespace App\Services;

use App\Models\HasilUji;
use App\Models\ParameterUji;
use Illuminate\Http\Request;

class ChartDataService
{
    /**
     * Mempersiapkan data untuk Inhouse Control Chart (View maupun Cetak PDF).
     *
     * @param Request $request
     * @param bool $isPdf
     * @return array
     */
    public function prepareInhouseControlData(Request $request, bool $isPdf = false, ?ParameterUji $param = null): array
    {
        $selectedParameter = null;
        $chartData = null;
        $hasilList = collect();
        $validList = collect();
        $stats = null;
        $viewName = 'parameter-uji.partials.generic';
        $pdfView = 'parameter-uji.pdf.pdf';

        $selectedParameter = $param;
        if (!$selectedParameter && $request->has('parameter_uji_id') && $request->input('parameter_uji_id') != '') {
            $selectedParameter = ParameterUji::findOrFail($request->input('parameter_uji_id'));
        }
        
        if ($selectedParameter) {

            // View Selection Logic (Moved up to prevent early return with wrong view)
            if ($selectedParameter->nama_parameter === 'IM' || str_contains($selectedParameter->nama_parameter, 'Inherent Moisture')) {
                $viewName = 'parameter-uji.partials.im';
                $pdfView = 'parameter-uji.pdf.pdf-im';
            } elseif ($selectedParameter->nama_parameter === 'ASH') {
                $viewName = 'parameter-uji.partials.ash';
                $pdfView = 'parameter-uji.pdf.pdf-ash';
            } elseif (str_contains($selectedParameter->nama_parameter, 'Bias Test')) {
                $viewName = 'parameter-uji.partials.bias-vm';
                $pdfView = 'parameter-uji.pdf.pdf-bias-vm';
            } elseif (str_contains($selectedParameter->nama_parameter, 'VM')) {
                $viewName = 'parameter-uji.partials.vm';
                $pdfView = 'parameter-uji.pdf.pdf-vm';
            } elseif (str_contains($selectedParameter->nama_parameter, 'Total Sulfur') || str_contains($selectedParameter->nama_parameter, 'TS')) {
                $viewName = 'parameter-uji.partials.ts';
                $pdfView = 'parameter-uji.pdf.pdf-ts';
            } elseif (str_contains($selectedParameter->nama_parameter, 'Calorific Value') || str_contains($selectedParameter->nama_parameter, 'CV')) {
                $viewName = 'parameter-uji.partials.cv';
                $pdfView = 'parameter-uji.pdf.pdf-cv';
            } elseif (str_contains($selectedParameter->nama_parameter, 'Ash Fusion Temperature') || str_contains($selectedParameter->nama_parameter, 'AFT')) {
                $viewName = 'parameter-uji.partials.aft';
                $pdfView = 'parameter-uji.pdf.pdf-aft';
            } elseif (str_contains($selectedParameter->nama_parameter, 'CHN')) {
                $viewName = 'parameter-uji.partials.chn';
                $pdfView = 'parameter-uji.pdf.pdf-chn';
            }

            $query = HasilUji::where('parameter_uji_id', $selectedParameter->parameter_uji_id)
                ->whereNotNull('nilai_hasil')
                ->orderBy('created_at', 'asc');

            $jenisGrafik = $request->input('jenis_grafik', $request->input('tab', 'in_house'));
            $query->where('jenis_kontrol', $jenisGrafik);

            if ($request->filled('tanggal_mulai')) {
                $query->whereDate('created_at', '>=', $request->input('tanggal_mulai'));
            }
            if ($request->filled('tanggal_akhir')) {
                $query->whereDate('created_at', '<=', $request->input('tanggal_akhir'));
            }

            if ($isPdf) {
                $hasilList = $query->with('penginput')->get();
            } else {
                $hasilList = $query->get();
            }

            $validList = $hasilList->where('status_berketerimaan', '!=', 'gagal_duplo')->values();
            $baseValidList = $validList;

            // Bias Test VM Fallback
            if ($selectedParameter->nama_parameter === 'Bias Test VM (Pt)') {
                $vmParam = ParameterUji::where('nama_parameter', 'VM')->first();
                if ($vmParam) {
                    $baseValidList = HasilUji::where('parameter_uji_id', $vmParam->parameter_uji_id)
                        ->where('status_berketerimaan', '!=', 'gagal_duplo')
                        ->where('status_berketerimaan', '!=', 'outlier')
                        ->whereNotNull('nilai_hasil')
                        ->get();
                }
            }

            if ($jenisGrafik === 'crm') {
                $evaluations = [];
                $chartValues = [];
                $chartLabels = [];
                $chartStatuses = [];
                $kodeAturan = [];
                
                // For CRM chart, limits change over time depending on the LOT used.
                // We will collect the data but we don't have a single mean/batasAtas line for the whole chart.
                // However, Chart.js can handle line segments, or we can just pass an array for the bound lines!
                $upperLimits = [];
                $lowerLimits = [];
                $centerLines = [];
                
                foreach ($validList as $model) {
                    $certValue = 0;
                    $certU = 0;
                    if ($model->kegiatan && $model->kegiatan->crm_katalog_id) {
                        $sert = \App\Models\CrmSertifikat::where('crm_katalog_id', $model->kegiatan->crm_katalog_id)
                            ->where('parameter_uji_id', $selectedParameter->parameter_uji_id)->first();
                        if ($sert) {
                            $certValue = $sert->cert_value;
                            $certU = $sert->cert_u;
                        }
                    }

                    $batasBawah = $certValue - $certU;
                    $batasAtas = $certValue + $certU;
                    $val = (float) $model->nilai_hasil;
                    
                    $status = ($val >= $batasBawah && $val <= $batasAtas) ? 'Terima' : 'Tolak';
                    
                    $evaluations[] = [
                        'hasil_uji_id' => $model->hasil_uji_id,
                        'cert_value' => $certValue,
                        'cert_u' => $certU,
                        'batas_bawah' => $batasBawah,
                        'batas_atas' => $batasAtas,
                        'status' => $status,
                        'alasan' => $status === 'Tolak' ? 'Di luar batas' : '-'
                    ];

                    $chartLabels[] = $model->created_at->format('d/m/Y');
                    $chartValues[] = $val;
                    $chartStatuses[] = ($status == 'Terima') ? 'hijau' : 'merah';
                    $kodeAturan[] = $status === 'Tolak' ? 'OUT_OF_BOUNDS' : null;
                    
                    $upperLimits[] = $batasAtas;
                    $lowerLimits[] = $batasBawah;
                    $centerLines[] = $certValue;
                }

                $stats = [
                    'mean' => $centerLines, // We pass array instead of scalar for dynamic lines
                    'plus3sd' => $upperLimits,
                    'minus3sd' => $lowerLimits,
                    'evaluations' => $evaluations
                ];

                $chartData = [
                    'labels' => $chartLabels,
                    'values' => $chartValues,
                    'statuses' => $chartStatuses,
                    'kodeAturan' => $kodeAturan,
                    'stats' => $stats,
                    'evaluations' => $evaluations
                ];

            } else {
                // Hitung Statistik Dasar
                $mean = (float) ($selectedParameter->mean ?? 0);
                $sd = (float) ($selectedParameter->sd ?? 0);

                $values = $baseValidList->pluck('nilai_hasil')->filter(fn($v) => is_numeric($v))->map(fn($v) => (float)$v)->toArray();
                
                if ($mean == 0 && $sd == 0) {
                    if (count($values) > 1) {
                        $mean = array_sum($values) / count($values);
                        $variance = 0.0;
                        foreach ($values as $val) {
                            $variance += pow($val - $mean, 2);
                        }
                        $variance /= (count($values) - 1);
                        $sd = sqrt($variance);
                    } else {
                        $mean = count($values) == 1 ? $values[0] : 0;
                        $sd = 0;
                    }
                }

                $evaluations = $this->generateWestgardTable($validList->pluck('nilai_hasil')->map(fn($v) => (float)$v)->toArray(), $mean, $sd);
                
                if (!$isPdf) {
                    // Untuk view, sertakan hasil_uji_id dan override
                    foreach ($evaluations as $i => &$eval) {
                        $model = $validList[$i];
                        $eval['hasil_uji_id'] = $model->hasil_uji_id;
                        if (!empty($model->override_status)) {
                            $eval['status'] = $model->override_status;
                            $eval['alasan'] = $model->keterangan_override ?: 'Di-override oleh analis';
                        }
                    }
                }

                $stats = [
                    'mean' => $mean,
                    'sd' => $sd,
                    'minus3sd' => $mean - (3 * $sd),
                    'minus2sd' => $mean - (2 * $sd),
                    'minus1sd' => $mean - (1 * $sd),
                    'plus1sd'  => $mean + (1 * $sd),
                    'plus2sd'  => $mean + (2 * $sd),
                    'plus3sd'  => $mean + (3 * $sd),
                    'evaluations' => $evaluations
                ];

                $chartData = [
                    'labels' => $validList->map(fn($h) => $h->created_at->format('d/m/Y'))->toArray(),
                    'values' => $validList->pluck('nilai_hasil')->map(fn($v) => (float) $v)->toArray(),
                    'statuses' => $validList->map(function ($h) {
                        if ($h->status_berketerimaan === 'outlier') return 'merah';
                        if ($h->kode_aturan_dilanggar === '1-2s') return 'kuning';
                        return 'hijau';
                    })->toArray(),
                    'kodeAturan' => $validList->pluck('kode_aturan_dilanggar')->toArray(),
                    'stats' => $stats,
                    'evaluations' => $evaluations
                ];
            }

            // Sub-Parameters Logic (AFT & CHN)
            if (str_contains($selectedParameter->nama_parameter, 'Ash Fusion Temperature') || str_contains($selectedParameter->nama_parameter, 'AFT')) {
                $subParams = ['IDT', 'ST', 'HT', 'FT'];
                $aftData = [];
                $aftStats = [];
                foreach ($subParams as $sub) {
                    $subValues = $baseValidList->map(function($h) use ($sub) {
                        $dm = is_array($h->data_mentah) ? $h->data_mentah : json_decode($h->data_mentah, true);
                        return $dm["Avg_{$sub}"] ?? null;
                    })->filter(fn($v) => is_numeric($v))->map(fn($v) => (float)$v)->toArray();

                    $subValues = array_values($subValues);

                    if (count($subValues) > 1) {
                        $m = array_sum($subValues) / count($subValues);
                        $v = 0.0;
                        foreach ($subValues as $val) $v += pow($val - $m, 2);
                        $v /= (count($subValues) - 1);
                        $s = sqrt($v);
                    } else {
                        $m = count($subValues) == 1 ? $subValues[0] : 0;
                        $s = 0;
                    }

                    $subChartValues = $validList->map(function($h) use ($sub) {
                        $dm = is_array($h->data_mentah) ? $h->data_mentah : json_decode($h->data_mentah, true);
                        return (float)($dm["Avg_{$sub}"] ?? 0);
                    })->toArray();

                    $subEvals = $this->generateWestgardTable($subChartValues, $m, $s);

                    $aftStats[$sub] = [
                        'mean'     => $m,
                        'sd'       => $s,
                        'plus1sd'  => $m + $s,
                        'plus2sd'  => $m + (2 * $s),
                        'plus3sd'  => $m + (3 * $s),
                        'minus1sd' => $m - $s,
                        'minus2sd' => $m - (2 * $s),
                        'minus3sd' => $m - (3 * $s),
                        'evaluations' => $subEvals
                    ];

                    $aftData[$sub] = [
                        'values' => $subChartValues,
                        'stats' => $aftStats[$sub],
                        'evaluations' => $subEvals
                    ];
                }
                $chartData['aft'] = $aftData;
                $stats['aft'] = $aftStats;
            } elseif (str_contains($selectedParameter->nama_parameter, 'CHN')) {
                $subParams = ['C', 'H', 'N'];
                $chnData = [];
                $chnStats = [];
                foreach ($subParams as $sub) {
                    $subValues = $baseValidList->map(function($h) use ($sub) {
                        $dm = is_array($h->data_mentah) ? $h->data_mentah : json_decode($h->data_mentah, true);
                        return $dm["Avg_{$sub}"] ?? null;
                    })->filter(fn($v) => is_numeric($v))->map(fn($v) => (float)$v)->toArray();

                    $subValues = array_values($subValues);

                    if (count($subValues) > 1) {
                        $m = array_sum($subValues) / count($subValues);
                        $v = 0.0;
                        foreach ($subValues as $val) $v += pow($val - $m, 2);
                        $v /= (count($subValues) - 1);
                        $s = sqrt($v);
                    } else {
                        $m = count($subValues) == 1 ? $subValues[0] : 0;
                        $s = 0;
                    }

                    $subChartValues = $validList->map(function($h) use ($sub) {
                        $dm = is_array($h->data_mentah) ? $h->data_mentah : json_decode($h->data_mentah, true);
                        return (float)($dm["Avg_{$sub}"] ?? 0);
                    })->toArray();

                    $chnStats[$sub] = [
                        'mean'     => $m,
                        'sd'       => $s,
                        'plus1sd'  => $m + $s,
                        'plus2sd'  => $m + (2 * $s),
                        'plus3sd'  => $m + (3 * $s),
                        'minus1sd' => $m - $s,
                        'minus2sd' => $m - (2 * $s),
                        'minus3sd' => $m - (3 * $s),
                    ];

                    $chnData[$sub] = [
                        'values' => $subChartValues,
                        'stats' => $chnStats[$sub]
                    ];
                }
                $chartData['chn'] = $chnData;
                $stats['chn'] = $chnStats;
            }

            
        }

        return [
            'selectedParameter' => $selectedParameter,
            'hasilList' => $hasilList,
            'validList' => $validList,
            'chartData' => $chartData,
            'stats' => $stats,
            'viewName' => $viewName,
            'pdfView' => $pdfView
        ];
    }

    /**
     * Membangun array evaluasi Westgard untuk setiap titik data
     */
    public function generateWestgardTable(array $values, float $mean, float $sd): array {
        $evaluations = [];
        $zScores = [];
        
        foreach ($values as $i => $val) {
            $z = $sd != 0 ? ($val - $mean) / $sd : 0;
            $zScores[] = $z;
            
            $eval = [
                '1_2s' => 'No',
                '1_3s' => 'No',
                '2_2s' => 'No',
                'R_4s' => 'No',
                '4_1s' => 'No',
                '10x'  => 'No',
                'status' => 'Terima',
                'alasan' => '-'
            ];
            
            $histori = array_reverse(array_slice($zScores, 0, $i)); 

            if (abs($z) > 2) {
                $eval['1_2s'] = 'Yes';
            }
            
            if (abs($z) > 3) {
                $eval['1_3s'] = 'Yes';
                $eval['status'] = 'Tolak';
                $eval['alasan'] = 'Melanggar 1(3s)';
            }
            
            if (count($histori) >= 1) {
                $zSeb = $histori[0];
                if (($z > 2 && $zSeb > 2) || ($z < -2 && $zSeb < -2)) {
                    $eval['2_2s'] = 'Yes';
                    if ($eval['status'] == 'Terima') {
                        $eval['status'] = 'Tolak';
                        $eval['alasan'] = 'Melanggar 2(2s)';
                    }
                }
            }
            
            if (count($histori) >= 1) {
                $zSeb = $histori[0];
                if (abs($z - $zSeb) > 4) {
                    $eval['R_4s'] = 'Yes';
                    if ($eval['status'] == 'Terima') {
                        $eval['status'] = 'Tolak';
                        $eval['alasan'] = 'Melanggar R(4s)';
                    }
                }
            }
            
            if (count($histori) >= 3) {
                $empat = array_merge([$z], array_slice($histori, 0, 3));
                $allPos = true; $allNeg = true;
                foreach($empat as $v) {
                    if ($v <= 1) $allPos = false;
                    if ($v >= -1) $allNeg = false;
                }
                if ($allPos || $allNeg) {
                    $eval['4_1s'] = 'Yes';
                    if ($eval['status'] == 'Terima') {
                        $eval['status'] = 'Tolak';
                        $eval['alasan'] = 'Melanggar 4(1s)';
                    }
                }
            }
            
            if (count($histori) >= 9) {
                $sepuluh = array_merge([$z], array_slice($histori, 0, 9));
                $allAtas = true; $allBawah = true;
                foreach($sepuluh as $v) {
                    if ($v <= 0) $allAtas = false;
                    if ($v >= 0) $allBawah = false;
                }
                if ($allAtas || $allBawah) {
                    $eval['10x'] = 'Yes';
                    if ($eval['status'] == 'Terima') {
                        $eval['status'] = 'Tolak';
                        $eval['alasan'] = 'Melanggar 10x';
                    }
                }
            }
            
            $evaluations[] = $eval;
        }
        
        return $evaluations;
    }
}
