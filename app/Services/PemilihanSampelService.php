<?php

namespace App\Services;

class PemilihanSampelService
{
    /**
     * Rentang batasan parameter berdasarkan Jenis Batubara (ADB atau DB).
     */
    const RENTANG = [
        'lignite' => [
            'tm'  => ['min' => 35.0, 'max' => 100.0],
            'mad' => ['min' => 18.0, 'max' => 100.0],
            'ash' => ['min' => 5.0,  'max' => 12.0],
            'vm'  => ['min' => 38.0, 'max' => 45.0],
            'ts'  => ['min' => 0.1,  'max' => 0.8],
            'gcv' => ['min' => 0.0,  'max' => 5000.0],
        ],
        'sub_bituminous' => [
            'tm'  => ['min' => 20.0, 'max' => 35.0],
            'mad' => ['min' => 10.0, 'max' => 18.0],
            'ash' => ['min' => 4.0,  'max' => 10.0],
            'vm'  => ['min' => 36.0, 'max' => 42.0],
            'ts'  => ['min' => 0.2,  'max' => 1.5],
            'gcv' => ['min' => 5000.0, 'max' => 6200.0],
        ],
        'bituminous' => [
            'tm'  => ['min' => 8.0,  'max' => 20.0],
            'mad' => ['min' => 3.0,  'max' => 9.0],
            'ash' => ['min' => 6.0,  'max' => 15.0],
            'vm'  => ['min' => 28.0, 'max' => 40.0],
            'ts'  => ['min' => 0.4,  'max' => 4.0],
            'gcv' => ['min' => 6200.0, 'max' => 7200.0],
        ],
        'anthracite' => [
            'tm'  => ['min' => 0.0, 'max' => 8.0],
            'mad' => ['min' => 0.0, 'max' => 3.0],
            'ash' => ['min' => 5.0, 'max' => 15.0],
            'vm'  => ['min' => 0.0, 'max' => 14.0],
            'ts'  => ['min' => 0.4, 'max' => 1.0],
            'gcv' => ['min' => 7200.0, 'max' => null],
        ],
    ];

    /**
     * Konversi dari ADB (Air Dried Basis) ke DB (Dry Basis)
     */
    public function convertAdbToDb(array $data): array
    {
        $mad = (float) ($data['mad'] ?? 0);
        $factor = ($mad < 100) ? (100 / (100 - $mad)) : 1; // Prevent division by zero

        $converted = [];
        $paramsToConvert = ['ash', 'vm', 'ts', 'gcv'];
        
        foreach ($paramsToConvert as $param) {
            if (isset($data[$param])) {
                $valAdb = (float) $data[$param];
                $converted[$param . '_db'] = round($valAdb * $factor, 4);
            }
        }
        
        return $converted;
    }

    /**
     * Memvalidasi apakah input memenuhi rentang jenis batubara.
     */
    public function validateRange(string $jenis, array $data): array
    {
        $errors = [];
        $rentang = self::RENTANG[$jenis] ?? null;

        if (!$rentang) {
            return ["Jenis batubara '{$jenis}' tidak valid."];
        }

        foreach (['tm', 'mad', 'ash', 'vm', 'ts', 'gcv'] as $param) {
            if (isset($data[$param]) && $data[$param] !== null) {
                $val = (float) $data[$param];
                $min = $rentang[$param]['min'];
                $max = $rentang[$param]['max'];

                if ($min !== null && $val < $min) {
                    $errors[] = strtoupper($param) . " terlalu rendah (min {$min}).";
                }
                if ($max !== null && $val > $max) {
                    $errors[] = strtoupper($param) . " terlalu tinggi (maks {$max}).";
                }
            }
        }

        return $errors;
    }

    /**
     * Validasi aturan silang parameter (Hukum Fisika).
     */
    public function validateCrossField(array $data): array
    {
        $errors = [];

        // 1. Hukum Fisika A: TM > MAD
        if (isset($data['tm']) && isset($data['mad'])) {
            if ((float)$data['tm'] <= (float)$data['mad']) {
                $errors[] = 'Hukum Fisika Gagal: Total Moisture (TM) harus lebih besar dari Moisture in Analysis Sample (MAD).';
            }
        }

        // 2. Hukum Fisika B: Proximate Balance (MAD + Ash + VM < 100)
        if (isset($data['mad']) && isset($data['ash']) && isset($data['vm'])) {
            $mad = (float)$data['mad'];
            $ash = (float)$data['ash'];
            $vm = (float)$data['vm'];
            
            $totalMav = $mad + $ash + $vm;
            if ($totalMav >= 100.0) {
                $errors[] = "Hukum Fisika Gagal: Total (MAD + Ash + VM) adalah {$totalMav}%. Nilai ini tidak boleh melebihi atau sama dengan 100% karena Fixed Carbon (FC) tidak boleh negatif atau nol.";
            }
        }

        return $errors;
    }
}
