<?php

namespace App\Services;

class StabilitasService
{
    /**
     * Hitung Uji Stabilitas berdasarkan perbandingan dengan Penetapan Target.
     * Menggunakan Pooled Standard Deviation (S_gab).
     *
     * @param array $stabilityData Hasil pengujian stabilitas (Y)
     * @param array $targetData Data histori target ['n' => nx, 'sum_sq' => sumSqX, 'mean' => meanX]
     * @return array Results
     */
    public function calculateTTest(array $stabilityData, array $targetData): array
    {
        $ny = count($stabilityData);
        if ($ny < 2) {
            return ['error' => true, 'message' => 'Minimal 2 pengujian stabilitas.'];
        }

        $nx = $targetData['n'] ?? 0;
        $sumSqX = $targetData['sum_sq'] ?? 0;
        $meanX = $targetData['mean'] ?? 0;

        if ($nx < 2) {
            return ['error' => true, 'message' => 'Data penetapan target tidak valid (n < 2).'];
        }

        // Mean Y (Stabilitas)
        $meanY = array_sum($stabilityData) / $ny;

        // Sum of Squares Y
        $sumSqY = 0;
        foreach ($stabilityData as $val) {
            $sumSqY += pow($val - $meanY, 2);
        }

        // S_gab (Pooled Standard Deviation)
        $df = $nx + $ny - 2;
        $sGab = sqrt(($sumSqX + $sumSqY) / $df);

        // t-hitung (Mengikuti persis instruksi kerja / SOP tanpa multiplier SE)
        $tHitung = $sGab > 0 ? abs($meanX - $meanY) / $sGab : 0;

        // t-tabel
        $tTabel = $this->getTTabel($df);

        return [
            'mean_stabilitas' => $meanY,
            'sd_stabilitas' => sqrt($sumSqY / ($ny - 1)),
            's_gab' => $sGab,
            't_hitung' => $tHitung,
            't_tabel' => $tTabel,
            'lolos' => $tHitung < $tTabel
        ];
    }

    /**
     * T-Tabel lookup untuk two-tailed alpha=0.05
     */
    private function getTTabel(int $df): float
    {
        $tTable = [
            1 => 12.706, 2 => 4.303, 3 => 3.182, 4 => 2.776, 5 => 2.571,
            6 => 2.447, 7 => 2.365, 8 => 2.306, 9 => 2.262, 10 => 2.228,
            11 => 2.201, 12 => 2.179, 13 => 2.160, 14 => 2.145, 15 => 2.131,
            16 => 2.120, 17 => 2.110, 18 => 2.101, 19 => 2.093, 20 => 2.086,
            21 => 2.080, 22 => 2.074, 23 => 2.069, 24 => 2.064, 25 => 2.060,
            26 => 2.056, 27 => 2.052, 28 => 2.048, 29 => 2.045, 30 => 2.042,
            31 => 2.040, 32 => 2.037, 33 => 2.035, 34 => 2.032, 35 => 2.030,
            36 => 2.028, 37 => 2.026, 38 => 2.024, 39 => 2.023, 40 => 2.021,
            60 => 2.000, 120 => 1.980
        ];

        if (isset($tTable[$df])) {
            return $tTable[$df];
        }

        // Cari pendekatan terdekat atau fallback
        return 2.0; 
    }
}
