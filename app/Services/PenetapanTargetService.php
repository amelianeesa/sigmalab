<?php

namespace App\Services;

class PenetapanTargetService
{
    /**
     * Menghitung nilai Mean dan Simpangan Baku dari array hasil uji analis.
     * Digunakan pada Tahap 4: Penetapan Nilai Target.
     * 
     * @param array $nilaiHasil Array of float dari hasil pengujian
     * @return array ['mean' => float, 'sd' => float, 'n' => int, 'sum_sq' => float]
     */
    public function calculateTarget(array $nilaiHasil): array
    {
        $n = count($nilaiHasil);
        
        if ($n < 2) {
            return [
                'mean' => 0,
                'sd' => 0,
                'n' => $n,
                'sum_sq' => 0
            ];
        }

        // Rata-rata (X-bar)
        $mean = array_sum($nilaiHasil) / $n;

        // Sum of Squares: Sum(Xi - X-bar)^2
        $sumSq = 0;
        foreach ($nilaiHasil as $val) {
            $sumSq += pow($val - $mean, 2);
        }

        // Sample Standard Deviation (sigma)
        $sd = sqrt($sumSq / ($n - 1));

        return [
            'mean' => $mean,
            'sd' => $sd,
            'n' => $n,
            'sum_sq' => $sumSq
        ];
    }
}
