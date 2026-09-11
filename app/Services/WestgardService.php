<?php

namespace App\Services;

use Illuminate\Support\Collection;

class WestgardService
{
    /**
     * Evaluasi nilai pengujian harian QC menggunakan Westgard Rules
     *
     * @param float $d1 Nilai uji 1 (Simplo)
     * @param float|null $d2 Nilai uji 2 (Duplo)
     * @param float $mean Nilai rata-rata target (dari Homogenitas)
     * @param float $sd Nilai standar deviasi (dari Homogenitas)
     * @param Collection $history 9 data QcHarian terakhir untuk parameter dan batch yang sama (diurutkan descending / terbaru di index 0)
     * @return array
     */
    public function evaluate(float $d1, ?float $d2, float $mean, float $sd, Collection $history): array
    {
        // Hitung nilai akhir pengujian hari ini
        $nilaiAkhir = $d2 !== null ? ($d1 + $d2) / 2 : $d1;

        // Jarak dari mean
        $diff = abs($nilaiAkhir - $mean);

        // 1. GATEKEEPER: Rule 1_2s
        // Jika nilai tidak melampaui 2SD, langsung Inlier
        if ($diff <= 2 * $sd) {
            return [
                'status' => 'inlier',
                'rule' => null,
                'message' => 'In-Control (Data normal)',
                'nilai_akhir' => $nilaiAkhir
            ];
        }

        // --- RENTETAN ATURAN PENOLAKAN (REJECTION RULES) ---

        // 2. Rule 1_3s
        // Apakah titik ini melampaui 3SD?
        if ($diff > 3 * $sd) {
            return [
                'status' => 'outlier',
                'rule' => '1_3s',
                'message' => 'Outlier: Titik data melampaui batas 3SD',
                'nilai_akhir' => $nilaiAkhir
            ];
        }

        // 3. Rule 2_2s
        // Apakah 2 titik berturut-turut melebihi +2SD atau -2SD?
        if ($history->count() >= 1) {
            $last = (float)$history->first()->nilai_akhir;
            if ($nilaiAkhir > $mean + 2 * $sd && $last > $mean + 2 * $sd) {
                return ['status' => 'outlier', 'rule' => '2_2s', 'message' => 'Outlier: 2 titik berturut-turut melebihi +2SD', 'nilai_akhir' => $nilaiAkhir];
            }
            if ($nilaiAkhir < $mean - 2 * $sd && $last < $mean - 2 * $sd) {
                return ['status' => 'outlier', 'rule' => '2_2s', 'message' => 'Outlier: 2 titik berturut-turut melebihi -2SD', 'nilai_akhir' => $nilaiAkhir];
            }
        }

        // 4. Rule R_4s
        // Apakah selisih antara D1 dan D2 lebih dari 4SD?
        if ($d2 !== null) {
            if (abs($d1 - $d2) > 4 * $sd) {
                return ['status' => 'outlier', 'rule' => 'R_4s', 'message' => 'Outlier: Selisih rentang antara D1 dan D2 melebihi 4SD', 'nilai_akhir' => $nilaiAkhir];
            }
        }

        // 5. Rule 4_1s
        // Apakah 4 titik berturut-turut lebih dari +1SD atau -1SD?
        if ($history->count() >= 3) {
            $points = [$nilaiAkhir, 
                       (float)$history[0]->nilai_akhir, 
                       (float)$history[1]->nilai_akhir, 
                       (float)$history[2]->nilai_akhir];
            
            $allAbove = true;
            $allBelow = true;
            foreach ($points as $p) {
                if ($p <= $mean + $sd) $allAbove = false;
                if ($p >= $mean - $sd) $allBelow = false;
            }
            if ($allAbove) {
                return ['status' => 'outlier', 'rule' => '4_1s', 'message' => 'Outlier: 4 titik berturut-turut melebihi +1SD', 'nilai_akhir' => $nilaiAkhir];
            }
            if ($allBelow) {
                return ['status' => 'outlier', 'rule' => '4_1s', 'message' => 'Outlier: 4 titik berturut-turut melebihi -1SD', 'nilai_akhir' => $nilaiAkhir];
            }
        }

        // 6. Rule 10_x
        // Apakah 10 titik berturut-turut berada di satu sisi dari Mean?
        if ($history->count() >= 9) {
            $points = [$nilaiAkhir];
            for ($i = 0; $i < 9; $i++) {
                $points[] = (float)$history[$i]->nilai_akhir;
            }
            
            $allAbove = true;
            $allBelow = true;
            foreach ($points as $p) {
                if ($p <= $mean) $allAbove = false;
                if ($p >= $mean) $allBelow = false;
            }
            if ($allAbove) {
                return ['status' => 'outlier', 'rule' => '10_x', 'message' => 'Outlier: 10 titik berturut-turut berada di atas Mean', 'nilai_akhir' => $nilaiAkhir];
            }
            if ($allBelow) {
                return ['status' => 'outlier', 'rule' => '10_x', 'message' => 'Outlier: 10 titik berturut-turut berada di bawah Mean', 'nilai_akhir' => $nilaiAkhir];
            }
        }

        // 7. Jika gerbang 1_2s tertembus tapi TIDAK ADA SATU PUN rejection rule yang tertembus
        return [
            'status' => 'warning',
            'rule' => '1_2s',
            'message' => 'Warning (1_2s): Titik melebihi 2SD namun lolos uji penolakan (murni variasi acak)',
            'nilai_akhir' => $nilaiAkhir
        ];
    }
}
