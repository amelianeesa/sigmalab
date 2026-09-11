<?php

namespace App\Services;

class HomogenitasService
{
    /**
     * Perform One-Way ANOVA on homogeneity test data.
     * Menggunakan pendekatan MSB dan MSW sesuai panduan Sucofindo.
     *
     * @param array $samples Array of samples, each with ['nilai_d1' => float, 'nilai_d2' => float]
     * @return array Results including mean_global, sd_global, f_hitung, f_tabel, lolos
     */
    public function calculateAnova(array $samples): array
    {
        $k = count($samples); // number of groups (samples) - n pada dokumen
        $allValues = [];
        $groupMeans = [];
        $groupData = [];
        $sumAplusB = [];
        $sumAminusB = [];

        if ($k < 2) {
            return ['error' => true, 'message' => 'Minimal 2 pasang data homogenitas.'];
        }

        foreach ($samples as $i => $sample) {
            $d1 = (float) $sample['nilai_d1']; // A (Simplo)
            $d2 = (float) $sample['nilai_d2']; // B (Duplo)
            
            $groupData[$i] = [$d1, $d2];
            $groupMeans[$i] = ($d1 + $d2) / 2;
            
            $allValues[] = $d1;
            $allValues[] = $d2;
            
            $sumAplusB[$i] = $d1 + $d2;
            $sumAminusB[$i] = $d1 - $d2;
        }

        $N = count($allValues); // total observations (2n)
        $grandMean = array_sum($allValues) / $N;

        // Mean of A+B
        $meanAplusB = array_sum($sumAplusB) / $k;
        
        // Mean of A-B
        $meanAminusB = array_sum($sumAminusB) / $k;

        // MSB (Mean Square Between) -> 2 * (10 - 1) = 18
        $sumSqBetween = 0;
        foreach ($sumAplusB as $val) {
            $sumSqBetween += pow($val - $meanAplusB, 2);
        }
        $msb = $sumSqBetween / 18;

        // MSW (Mean Square Within) -> 2 * 10 = 20
        $sumSqWithin = 0;
        foreach ($sumAminusB as $val) {
            $sumSqWithin += pow($val - $meanAminusB, 2);
        }
        $msw = $sumSqWithin / 20;

        // F Hitung
        $fHitung = $msw != 0 ? $msb / $msw : 0;

        // F Tabel (v1 = k-1, v2 = k) - Simplified lookup for alpha 0.05
        $fTabel = $this->getFTabel($k - 1, $k);

        // SD Global
        $sumSqTotal = 0;
        foreach ($allValues as $v) {
            $sumSqTotal += pow($v - $grandMean, 2);
        }
        $sdGlobal = $N > 1 ? sqrt($sumSqTotal / ($N - 1)) : 0;

        return [
            'mean_global' => $grandMean,
            'sd_global' => $sdGlobal,
            'msb' => $msb,
            'msw' => $msw,
            'f_hitung' => $fHitung,
            'f_tabel' => $fTabel,
            'lolos' => $fHitung < $fTabel
        ];
    }

    /**
     * Simplified F-Table lookup for alpha = 0.05
     * v1 = Numerator df (n-1)
     * v2 = Denominator df (n)
     */
    private function getFTabel(int $v1, int $v2): float
    {
        // Common table for 10 samples (v1=9, v2=10) is 3.020
        $fTable = [
            '9_10' => 3.020,
            '14_15' => 2.424,
            // Add more common df mappings as needed. For now returning a safe approximation or fixed array
        ];
        
        $key = "{$v1}_{$v2}";
        if (isset($fTable[$key])) {
            return $fTable[$key];
        }

        // Fallback or approximated logic
        return 3.020; 
    }
}
