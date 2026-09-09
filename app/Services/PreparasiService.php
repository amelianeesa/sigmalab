<?php

namespace App\Services;

class PreparasiService
{
    /**
     * Memeriksa apakah tabel equilibrium sudah mencapai bobot konstan.
     * Bobot konstan tercapai jika laju kehilangan bobot <= 0.1% per jam.
     * 
     * @param array $dataEquilibrium Array [{jam, berat, selisih}, ...]
     * @return bool
     */
    public function isEquilibriumReached(array $dataEquilibrium): bool
    {
        if (count($dataEquilibrium) < 2) {
            return false;
        }

        $last = end($dataEquilibrium);
        $prev = prev($dataEquilibrium);

        if (!isset($last['jam']) || !isset($last['berat']) || !isset($prev['jam']) || !isset($prev['berat'])) {
            return false;
        }

        $w2 = (float) $last['berat'];
        $w1 = (float) $prev['berat'];
        
        if ($w1 <= 0) return false;

        $t2 = strtotime($last['jam']);
        $t1 = strtotime($prev['jam']);

        if ($t2 === false || $t1 === false) return false;

        $diffSeconds = $t2 - $t1;
        // Jika beda hari (t2 < t1)
        if ($diffSeconds <= 0) {
            $diffSeconds += 24 * 3600;
        }

        $diffHours = $diffSeconds / 3600;
        if ($diffHours == 0) return false; // Prevent division by zero

        $rate = (abs($w2 - $w1) / $w1) * 100 / $diffHours;

        return $rate <= 0.1;
    }

    /**
     * Membuat daftar label/kode batch untuk botol.
     * Contoh: INH STD GA-26-001
     * 
     * @param string $kodeBatch Contoh: "INH STD GA-26"
     * @param int $jumlahBotol Contoh: 50
     * @param int $nomorAwal Contoh: 1
     * @return array
     */
    public function generateBatchCodes(string $kodeBatch, int $jumlahBotol, int $nomorAwal = 1): array
    {
        $codes = [];
        for ($i = 0; $i < $jumlahBotol; $i++) {
            $nomor = $nomorAwal + $i;
            $padded = str_pad($nomor, 3, '0', STR_PAD_LEFT);
            $codes[] = "{$kodeBatch}-{$padded}";
        }
        return $codes;
    }

    /**
     * Mengacak urutan baca instrumen untuk 20 porsi pengujian homogenitas.
     * 
     * @param int $jumlahBotol Jumlah grup/botol (default 10)
     * @param int $jumlahPorsiPerBotol Simplo & Duplo (default 2)
     * @return array Urutan yang diacak (contoh: ["1.1", "4.2", "10.1", ...])
     */
    public function generateUrutanInstrumen(int $jumlahBotol = 10, int $jumlahPorsiPerBotol = 2): array
    {
        $porsi = [];
        for ($i = 1; $i <= $jumlahBotol; $i++) {
            for ($j = 1; $j <= $jumlahPorsiPerBotol; $j++) {
                $porsi[] = "{$i}.{$j}";
            }
        }
        
        shuffle($porsi);
        return $porsi;
    }
}
