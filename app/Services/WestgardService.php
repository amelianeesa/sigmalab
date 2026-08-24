<?php

namespace App\Services;

use App\Models\HasilUji;
use App\Models\ParameterUji;

/**
 * Westgard Multi-Rule Quality Control Engine
 *
 * Mengevaluasi nilai kontrol baru berdasarkan 6 aturan Westgard secara bertingkat:
 * 1-2s (warning) → 1-3s → 2-2s → R-4s → 4-1s → 10x̄
 *
 * Referensi: James O. Westgard, "Basic QC Practices", 4th Edition
 */
class WestgardService
{
    /**
     * Evaluasi nilai kontrol baru terhadap aturan Westgard.
     *
     * @param float        $nilaiBaru    Nilai hasil uji yang baru diinput
     * @param ParameterUji $parameter    Parameter uji beserta mean, sd, dan aturan_aktif
     * @return array{status: string, kode: ?string, z_score: ?float, pesan: ?string}
     */
    public function evaluate(float $nilaiBaru, ParameterUji $parameter): array
    {
        // Periksa apakah mean dan sd sudah dikonfigurasi
        if (empty($parameter->mean) || empty($parameter->sd) || $parameter->sd == 0) {
            // Fallback ke logika LCL/UCL sederhana (mode lama)
            return $this->fallbackEvaluation($nilaiBaru, $parameter);
        }

        $mean = (float) $parameter->mean;
        $sd   = (float) $parameter->sd;

        // Langkah 0: Hitung Z-Score
        $z = ($nilaiBaru - $mean) / $sd;
        
        // Mencegah error DB "Numeric value out of range" untuk tipe decimal(8,4)
        if ($z > 9999.9999) $z = 9999.9999;
        if ($z < -9999.9999) $z = -9999.9999;

        // Langkah 1: Cek Warning 1-2s
        if (abs($z) <= 2) {
            return [
                'status'  => 'inlier',
                'kode'    => null,
                'z_score' => round($z, 4),
                'pesan'   => null,
            ];
        }

        // Warning 1-2s terpicu! Ambil histori untuk evaluasi lanjutan
        $aturanAktif = $parameter->aturan_aktif ?? ['1-3s', '2-2s', 'R-4s', '4-1s', '10x'];
        $histori     = $this->getHistoriZScores($parameter, $mean, $sd, 10);

        // Langkah 2: Evaluasi 5 aturan secara berurutan
        // Aturan 1-3s: Satu titik data melewati ±3 SD
        if (in_array('1-3s', $aturanAktif) && abs($z) > 3) {
            return [
                'status'  => 'outlier',
                'kode'    => '1-3s',
                'z_score' => round($z, 4),
                'pesan'   => 'Pelanggaran 1₃s — Nilai melewati ±3 SD. Kemungkinan gross error. Hasil TIDAK boleh dikeluarkan.',
            ];
        }

        // Aturan 2-2s: Dua titik berturut-turut melewati ±2 SD di sisi yang sama
        if (in_array('2-2s', $aturanAktif) && count($histori) >= 1) {
            $zSebelum = $histori[0];
            if (
                ($z > 2 && $zSebelum > 2) ||
                ($z < -2 && $zSebelum < -2)
            ) {
                return [
                    'status'  => 'outlier',
                    'kode'    => '2-2s',
                    'z_score' => round($z, 4),
                    'pesan'   => 'Pelanggaran 2₂s — Dua nilai berturut-turut melewati ±2 SD di sisi yang sama. Terdeteksi systematic error.',
                ];
            }
        }

        // Aturan R-4s: Selisih antara dua titik berturut-turut > 4 SD
        if (in_array('R-4s', $aturanAktif) && count($histori) >= 1) {
            $zSebelum = $histori[0];
            if (abs($z - $zSebelum) > 4) {
                return [
                    'status'  => 'outlier',
                    'kode'    => 'R-4s',
                    'z_score' => round($z, 4),
                    'pesan'   => 'Pelanggaran R₄s — Selisih dua nilai berturut-turut melebihi 4 SD. Terdeteksi random error.',
                ];
            }
        }

        // Aturan 4-1s: 4 titik terakhir berturut-turut melewati ±1 SD di sisi yang sama
        if (in_array('4-1s', $aturanAktif) && count($histori) >= 3) {
            $empat = array_merge([$z], array_slice($histori, 0, 3));
            $semuaPositif = true;
            $semuaNegatif = true;
            foreach ($empat as $val) {
                if ($val <= 1) $semuaPositif = false;
                if ($val >= -1) $semuaNegatif = false;
            }
            if ($semuaPositif || $semuaNegatif) {
                return [
                    'status'  => 'outlier',
                    'kode'    => '4-1s',
                    'z_score' => round($z, 4),
                    'pesan'   => 'Pelanggaran 4₁s — 4 nilai berturut-turut melewati ±1 SD di sisi yang sama. Terdeteksi bias analitik — pertimbangkan kalibrasi ulang.',
                ];
            }
        }

        // Aturan 10x̄: 10 titik terakhir berturut-turut di sisi yang sama dari mean
        if (in_array('10x', $aturanAktif) && count($histori) >= 9) {
            $sepuluh = array_merge([$z], array_slice($histori, 0, 9));
            $semuaDiAtas = true;
            $semuaDiBawah = true;
            foreach ($sepuluh as $val) {
                if ($val <= 0) $semuaDiAtas = false;
                if ($val >= 0) $semuaDiBawah = false;
            }
            if ($semuaDiAtas || $semuaDiBawah) {
                return [
                    'status'  => 'outlier',
                    'kode'    => '10x',
                    'z_score' => round($z, 4),
                    'pesan'   => 'Pelanggaran 10x̄ — 10 nilai berturut-turut berada di sisi yang sama dari mean. Terdeteksi shift/trend.',
                ];
            }
        }

        // Semua aturan "No" → false alarm, tetap In-Control
        return [
            'status'  => 'inlier',
            'kode'    => '1-2s',  // Tandai bahwa sempat memicu warning 1-2s
            'z_score' => round($z, 4),
            'pesan'   => null,
        ];
    }

    /**
     * Ambil z-score dari N data histori terakhir untuk parameter yang sama.
     * Diurutkan dari yang paling baru ke paling lama.
     *
     * @return float[]
     */
    private function getHistoriZScores(ParameterUji $parameter, float $mean, float $sd, int $limit): array
    {
        $hasilList = HasilUji::where('parameter_uji_id', $parameter->parameter_uji_id)
            ->whereNotNull('nilai_hasil')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->pluck('nilai_hasil');

        return $hasilList->map(function ($nilai) use ($mean, $sd) {
            return ($nilai - $mean) / $sd;
        })->toArray();
    }

    /**
     * Evaluasi fallback menggunakan LCL/UCL atau batas_bawah/batas_atas
     * (untuk parameter yang belum dikonfigurasi mean & SD-nya).
     */
    private function fallbackEvaluation(float $nilaiBaru, ParameterUji $parameter): array
    {
        $batasBawah = $parameter->lcl ?? $parameter->batas_bawah;
        $batasAtas  = $parameter->ucl ?? $parameter->batas_atas;

        // Jika tidak ada batas sama sekali, anggap inlier
        if (is_null($batasBawah) && is_null($batasAtas)) {
            return [
                'status'  => 'inlier',
                'kode'    => null,
                'z_score' => null,
                'pesan'   => null,
            ];
        }

        $inBounds = true;
        if (!is_null($batasBawah) && $nilaiBaru < $batasBawah) $inBounds = false;
        if (!is_null($batasAtas)  && $nilaiBaru > $batasAtas)  $inBounds = false;

        if ($inBounds) {
            return [
                'status'  => 'inlier',
                'kode'    => null,
                'z_score' => null,
                'pesan'   => null,
            ];
        }

        return [
            'status'  => 'outlier',
            'kode'    => null,
            'z_score' => null,
            'pesan'   => "Nilai di luar batas kendali ({$batasBawah} - {$batasAtas}).",
        ];
    }
}
