<?php

namespace App\Services;

use App\Models\TitikKalibrasi;

class KalibrasiService
{
    public function hitungKoreksi($alatId, $kategori, $pembacaanInput)
    {
        $titik = TitikKalibrasi::where('alat_id', $alatId)
            ->where('kategori', $kategori)
            ->orderBy('equipment_reading', 'asc')
            ->get();

        if ($titik->isEmpty()) {
            return $pembacaanInput; 
        }

        if ($pembacaanInput <= $titik->first()->equipment_reading) {
            return $titik->first()->standard_reading;
        }

        if ($pembacaanInput >= $titik->last()->equipment_reading) {
            return $titik->last()->standard_reading;
        }

        // Rumus Interpolasi Linear: Y = Y1 + ((X - X1) / (X2 - X1)) * (Y2 - Y1)
        for ($i = 0; $i < count($titik) - 1; $i++) {
            $x1 = $titik[$i]->equipment_reading;
            $y1 = $titik[$i]->standard_reading;
            $x2 = $titik[$i + 1]->equipment_reading;
            $y2 = $titik[$i + 1]->standard_reading;

            if ($pembacaanInput >= $x1 && $pembacaanInput <= $x2) {
                $hasil = $y1 + (($pembacaanInput - $x1) / ($x2 - $x1)) * ($y2 - $y1);
                return round($hasil, 2);
            }
        }

        return $pembacaanInput;
    }
}