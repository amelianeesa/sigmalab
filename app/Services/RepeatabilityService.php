<?php

namespace App\Services;

class RepeatabilityService
{
    /**
     * Menghitung batas toleransi repeatability uji duplo berdasarkan metode acuan dan parameter.
     * 
     * @param string $metode 'astm' atau 'iso'
     * @param string $namaParameter Nama parameter uji dari database
     * @param float $rataRata Rata-rata dari uji duplo (X-bar)
     * @param string|null $jenisBatubara Opsional, digunakan untuk parameter tertentu seperti VM
     * @return float Batas toleransi maksimal absolut (r)
     */
    public function getLimit(string $metode, string $namaParameter, float $rataRata, ?string $jenisBatubara = null): float
    {
        $namaLower = strtolower($namaParameter);

        // MAD (Moisture in Analysis Sample)
        if (str_contains($namaLower, 'moisture') || str_contains($namaLower, 'mad')) {
            if ($metode === 'astm') {
                return 0.09 + (0.01 * $rataRata);
            }
            if ($metode === 'iso') {
                return $rataRata < 5.0 ? 0.10 : 0.15;
            }
        }

        // Ash Content
        if (str_contains($namaLower, 'ash') || str_contains($namaLower, 'abu')) {
            if ($metode === 'astm') {
                return 0.20; // Batas standar, jika abu tinggi bisa 0.3-0.5 tapi default 0.20
            }
            if ($metode === 'iso') {
                return $rataRata < 10.0 ? 0.20 : (0.02 * $rataRata);
            }
        }

        // Volatile Matter
        if (str_contains($namaLower, 'volatile') || str_contains($namaLower, 'vm')) {
            if ($metode === 'astm') {
                $isLowRank = in_array($jenisBatubara, ['sub_bituminous', 'lignite']);
                return $isLowRank ? 0.70 : 0.30;
            }
            if ($metode === 'iso') {
                return $rataRata < 10.0 ? 0.30 : 0.50;
            }
        }

        // Total Sulfur
        if (str_contains($namaLower, 'sulfur') || str_contains($namaLower, 'ts')) {
            if ($metode === 'astm') {
                return 0.05; // Asumsi sulfur < 2%
            }
            if ($metode === 'iso') {
                return 0.02 + (0.03 * $rataRata);
            }
        }

        // GCV (Gross Calorific Value)
        if (str_contains($namaLower, 'calorific') || str_contains($namaLower, 'gcv') || str_contains($namaLower, 'kalori')) {
            if ($metode === 'astm') {
                return 50.0; // Kal/g
            }
            if ($metode === 'iso') {
                return 120.0; // J/g (perlu diperhatikan satuannya saat output UI)
            }
        }

        // Fallback default (MAD ASTM)
        return 0.09 + (0.01 * $rataRata);
    }
}
