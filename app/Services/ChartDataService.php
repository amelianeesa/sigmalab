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
    public function prepareInhouseControlData(Request $request, bool $isPdf = false): array
    {
        $selectedParameter = null;
        $chartData = null;
        $hasilList = collect();
        $validList = collect();
        $stats = null;
        $viewName = 'hasil-uji.control-chart';
        $pdfView = 'inhouse-control.pdf';

        if ($request->has('parameter_uji_id') && $request->input('parameter_uji_id') != '') {
            $selectedParameter = ParameterUji::findOrFail($request->input('parameter_uji_id'));

            $query = HasilUji::where('parameter_uji_id', $selectedParameter->parameter_uji_id)
                ->whereNotNull('nilai_hasil')
                ->orderBy('created_at', 'asc');

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

            // Hitung Statistik Dasar
            $mean = (float) ($selectedParameter->mean ?? 0);
            $sd = (float) ($selectedParameter->sd ?? 0);

            // Jika PDF, gunakan histori array_sum (Sesuai logic lama, meski Fixed Base lebih baik. Tapi mari kita satukan: gunakan Fixed Base jika ada)
            // Ternyata di logic lama PDF menghitung mean & SD dinamis, sedangkan View pakai Fixed Base!
            // Kita satukan: jika ada Fixed Base, pakai itu. Kalau tidak, hitung dinamis.
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

            // View Selection Logic
            if ($selectedParameter->nama_parameter === 'IM' || str_contains($selectedParameter->nama_parameter, 'Inherent Moisture')) {
                $viewName = 'inhouse-control.im';
                $pdfView = 'inhouse-control.pdf-im';
            } elseif ($selectedParameter->nama_parameter === 'ASH') {
                $viewName = 'inhouse-control.ash';
                $pdfView = 'inhouse-control.pdf-ash';
            } elseif (str_contains($selectedParameter->nama_parameter, 'Bias Test')) {
                $viewName = 'inhouse-control.bias-vm';
                $pdfView = 'inhouse-control.pdf-bias-vm';
            } elseif (str_contains($selectedParameter->nama_parameter, 'VM')) {
                $viewName = 'inhouse-control.vm';
                $pdfView = 'inhouse-control.pdf-vm';
            } elseif (str_contains($selectedParameter->nama_parameter, 'Total Sulfur') || str_contains($selectedParameter->nama_parameter, 'TS')) {
                $viewName = 'inhouse-control.ts';
                $pdfView = 'inhouse-control.pdf-ts';
            } elseif (str_contains($selectedParameter->nama_parameter, 'Calorific Value') || str_contains($selectedParameter->nama_parameter, 'CV')) {
                $viewName = 'inhouse-control.cv';
                $pdfView = 'inhouse-control.pdf-cv';
            } elseif (str_contains($selectedParameter->nama_parameter, 'Ash Fusion Temperature') || str_contains($selectedParameter->nama_parameter, 'AFT')) {
                $viewName = 'inhouse-control.aft';
                $pdfView = 'inhouse-control.pdf-aft';
            } elseif (str_contains($selectedParameter->nama_parameter, 'CHN')) {
                $viewName = 'inhouse-control.chn';
                $pdfView = 'inhouse-control.pdf-chn';
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
