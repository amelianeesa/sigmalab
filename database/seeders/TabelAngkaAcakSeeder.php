<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabelAngkaAcak;

class TabelAngkaAcakSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['urutan' => 1, 'nomor_botol' => 23],
            ['urutan' => 2, 'nomor_botol' => 48],
            ['urutan' => 3, 'nomor_botol' => 19],
            ['urutan' => 4, 'nomor_botol' => 27],
            ['urutan' => 5, 'nomor_botol' => 18],
            ['urutan' => 6, 'nomor_botol' => 38],
            ['urutan' => 7, 'nomor_botol' => 6],
            ['urutan' => 8, 'nomor_botol' => 65],
            ['urutan' => 9, 'nomor_botol' => 31],
            ['urutan' => 10, 'nomor_botol' => 7],
        ];

        foreach ($data as $row) {
            // updateOrCreate supaya aman dijalankan berkali-kali:
            // kalau urutan sudah ada, nomor_botol-nya diperbaiki;
            // kalau belum ada, baris baru dibuat.
            TabelAngkaAcak::updateOrCreate(
                ['urutan' => $row['urutan']],
                ['nomor_botol' => $row['nomor_botol']]
            );
        }
    }
}