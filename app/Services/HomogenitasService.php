<?php

namespace App\Services;

class HomogenitasService
{
    /**
     * Tabel F kritis (alpha = 0.05) untuk v1 = n-1, v2 = n.
     * n = jumlah kemasan/grup yang diuji (bukan jumlah total observasi).
     * Dihitung dari distribusi F standar, n = 3 s.d. 30.
     * Nilai n=10 (3.0204) sudah diverifikasi cocok dengan Excel resmi
     * (FOR/COAL-OPS/214) yang menyebutkan F tabel = 3.020 untuk v1=9, v2=10.
     */
    private const TABEL_F = [
        3  => 9.5521,
        4  => 6.5914,
        5  => 5.1922,
        6  => 4.3874,
        7  => 3.8660,
        8  => 3.5005,
        9  => 3.2296,
        10 => 3.0204,
        11 => 2.8536,
        12 => 2.7173,
        13 => 2.6037,
        14 => 2.5073,
        15 => 2.4244,
        16 => 2.3522,
        17 => 2.2888,
        18 => 2.2325,
        19 => 2.1823,
        20 => 2.1370,
        21 => 2.0960,
        22 => 2.0587,
        23 => 2.0246,
        24 => 1.9932,
        25 => 1.9643,
        26 => 1.9375,
        27 => 1.9126,
        28 => 1.8894,
        29 => 1.8677,
        30 => 1.8474,
    ];

    /**
     * Perform One-Way ANOVA on homogeneity test data.
     * Menggunakan pendekatan MSB dan MSW sesuai panduan Sucofindo.
     * Sekarang generik untuk n (jumlah kemasan) berapapun, minimal 3.
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

        // Minimal 3 kemasan supaya ANOVA masih bermakna secara statistik
        // (v1 = k-1 butuh minimal 2 derajat kebebasan).
        if ($k < 3) {
            return ['error' => true, 'message' => 'Minimal 3 pasang data (kemasan) diperlukan untuk uji homogenitas.'];
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

        $N = count($allValues); // total observations (2k)
        $grandMean = array_sum($allValues) / $N;

        // Mean of A+B
        $meanAplusB = array_sum($sumAplusB) / $k;

        // Mean of A-B
        $meanAminusB = array_sum($sumAminusB) / $k;

        // MSB (Mean Square Between) -> generik: 2 * (k - 1)
        $sumSqBetween = 0;
        foreach ($sumAplusB as $val) {
            $sumSqBetween += pow($val - $meanAplusB, 2);
        }
        $msb = $sumSqBetween / (2 * ($k - 1));

        // MSW (Mean Square Within) -> generik: 2 * k
        $sumSqWithin = 0;
        foreach ($sumAminusB as $val) {
            $sumSqWithin += pow($val - $meanAminusB, 2);
        }
        $msw = $sumSqWithin / (2 * $k);

        // F Hitung
        $fHitung = $msw != 0 ? $msb / $msw : 0;

        // F Tabel (v1 = k-1, v2 = k) - lookup dinamis berdasarkan k
        $fTabel = $this->getFTabel($k);

        // --- SD Global ---
        // Rumus resmi Excel (FOR/COAL-OPS/214, sel K32): SD = SQRT((MSB - MSW) / 2)
        // Ini BERBEDA dari rumus lama (simple sample SD dari seluruh nilai mentah)
        // yang dipakai sebelumnya. Silakan cek catatan di bawah kalau ingin
        // membandingkan / rollback ke rumus lama.
        $selisihMsbMsw = $msb - $msw;
        $sdGlobal = $selisihMsbMsw > 0 ? sqrt($selisihMsbMsw / 2) : 0;

        // --- Rumus lama (simple sample SD), disimpan sebagai referensi/komentar ---
        // $sumSqTotal = 0;
        // foreach ($allValues as $v) {
        //     $sumSqTotal += pow($v - $grandMean, 2);
        // }
        // $sdGlobalLama = $N > 1 ? sqrt($sumSqTotal / ($N - 1)) : 0;

        return [
            'n' => $k,
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
     * Ekspos seluruh tabel F (n => nilai kritis) untuk dipakai di frontend
     * (live preview JS), supaya satu-satunya sumber kebenaran tetap di sini
     * dan preview tidak pernah berbeda dengan hasil kalkulasi backend.
     */
    public function getTabelF(): array
    {
        return self::TABEL_F;
    }

    /**
     * F-Table lookup untuk alpha = 0.05, v1 = n-1, v2 = n.
     * Karena v1 dan v2 selalu diturunkan dari n yang sama, tabel F 2 dimensi
     * disederhanakan jadi lookup 1 dimensi berdasarkan n (jumlah kemasan).
     */
    private function getFTabel(int $n): float
    {
        if (isset(self::TABEL_F[$n])) {
            return self::TABEL_F[$n];
        }

        $keys = array_keys(self::TABEL_F);
        $minN = min($keys);
        $maxN = max($keys);

        if ($n < $minN) {
            // Seharusnya tidak pernah kejadian karena sudah divalidasi $k < 3 di atas
            return self::TABEL_F[$minN];
        }

        if ($n > $maxN) {
            // n di luar rentang tabel (>30 kemasan, jarang terjadi di praktik).
            // Pakai nilai n terbesar yang ada sebagai pendekatan konservatif
            // (F kritis makin kecil & melandai seiring n membesar).
            return self::TABEL_F[$maxN];
        }

        // Fallback terakhir: interpolasi linear antara dua n terdekat
        $lower = null;
        $upper = null;
        foreach ($keys as $key) {
            if ($key <= $n) $lower = $key;
            if ($key >= $n && $upper === null) $upper = $key;
        }
        if ($lower !== null && $upper !== null && $lower !== $upper) {
            $fLower = self::TABEL_F[$lower];
            $fUpper = self::TABEL_F[$upper];
            $ratio = ($n - $lower) / ($upper - $lower);
            return $fLower + ($fUpper - $fLower) * $ratio;
        }

        return self::TABEL_F[$maxN];
    }
}