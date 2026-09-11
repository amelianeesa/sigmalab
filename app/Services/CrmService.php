<?php

namespace App\Services;

use App\Models\ParameterUji;

class CrmService
{
    /**
     * Evaluate a CRM test result
     *
     * @param float|null $nilai_hasil
     * @param float|null $certValue
     * @param float|null $certU
     * @return array
     */
    public function evaluate($nilai_hasil, $certValue, $certU): array
    {
        if ($nilai_hasil === null || $certValue === null || $certU === null) {
            return [
                'status_berketerimaan' => 'outlier',
                'kode_aturan_dilanggar' => 'NO_LIMITS'
            ];
        }
        
        $batasBawah = $certValue - $certU;
        $batasAtas = $certValue + $certU;

        if ($nilai_hasil >= $batasBawah && $nilai_hasil <= $batasAtas) {
            $status = 'inlier';
            $kodeAturan = null;
        } else {
            $status = 'outlier';
            $kodeAturan = 'OUT_OF_BOUNDS';
        }

        return [
            'status_berketerimaan' => $status,
            'kode_aturan_dilanggar' => $kodeAturan
        ];
    }
}