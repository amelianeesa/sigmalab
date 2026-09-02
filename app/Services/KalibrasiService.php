<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class KalibrasiService
{
    public function hitungKoreksi(string $codeAlat, string $parameter, float $nilaiPembacaan): float
    {
        // Ambil data acuan kalibrasi dari database (misal tabel alat_thermo_kalibrasi)
        $titikKalibrasi = DB::table('alat_thermo_kalibrasi')
            ->where('code_alat', $codeAlat)
            ->where('parameter', $parameter)
            ->orderBy('equipment_reading', 'asc')
            ->get();

        if ($titikKalibrasi->isEmpty()) {
            return $nilaiPembacaan; // Fallback jika belum ada data tabel kalibrasi
        }

        $palingBawah = $titikKalibrasi->first();
        $palingAtas = $titikKalibrasi->last();

        if ($nilaiPembacaan <= $palingBawah->equipment_reading) {
            return (float) $palingBawah->standard_reading;
        }
        if ($nilaiPembacaan >= $palingAtas->equipment_reading) {
            return (float) $palingAtas->standard_reading;
        }

        $lower = null;
        $upper = null;

        for ($i = 0; $i < count($titikKalibrasi) - 1; $i++) {
            if ($nilaiPembacaan >= $titikKalibrasi[$i]->equipment_reading && $nilaiPembacaan <= $titikKalibrasi[$i+1]->equipment_reading) {
                $lower = $titikKalibrasi[$i];
                $upper = $titikKalibrasi[$i+1];
                break;
            }
        }

        if (!$lower || !$upper) {
            return $nilaiPembacaan;
        }

        // Rumus Interpolasi Linear
        $x = $nilaiPembacaan;
        $x1 = $lower->equipment_reading;
        $x2 = $upper->equipment_reading;
        $y1 = $lower->standard_reading;
        $y2 = $upper->standard_reading;

        if ($x2 == $x1) {
            return (float) $y1;
        }

        $y = $y1 + ($x - $x1) * (($y2 - $y1) / ($x2 - $x1));

        return round($y, 2);
    }
}