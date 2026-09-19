<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterRumusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rumus = [
            [
                'kode_rumus' => 'cv_total',
                'nama_rumus' => 'Perhitungan Total TS pada CV',
                'rumus_teks' => 'round(((PR) - (14.3 * 0.0699 * VT) - (2.3 * LF) - (13.2 * TS * SM)) / SM)',
                'keterangan_variabel' => "Variabel tersedia:\nPR = Pengurangan Berat\nVT = Volume Titrasi\nLF = Titration Factor\nTS = Total Sulfur\nSM = Sample Mass\nFungsi tersedia: round(x), abs(x), dll.",
            ],
            [
                'kode_rumus' => 'im_abs_diff',
                'nama_rumus' => 'Batas Toleransi Absolute Difference IM',
                'rumus_teks' => '0.09 + (0.1 * AVG)',
                'keterangan_variabel' => "Variabel tersedia:\nAVG = Nilai Average (%)",
            ],
            [
                'kode_rumus' => 'ash_abs_diff',
                'nama_rumus' => 'Batas Toleransi Absolute Difference ASH',
                'rumus_teks' => '0.09 + (0.1 * AVG)',
                'keterangan_variabel' => "Variabel tersedia:\nAVG = Nilai Average (%)",
            ],
            [
                'kode_rumus' => 'vm_abs_diff',
                'nama_rumus' => 'Batas Toleransi Absolute Difference VM',
                'rumus_teks' => '0.09 + (0.1 * AVG)',
                'keterangan_variabel' => "Variabel tersedia:\nAVG = Nilai Average (%)",
            ]
        ];

        foreach ($rumus as $r) {
            \App\Models\MasterRumus::updateOrCreate(
                ['kode_rumus' => $r['kode_rumus']],
                $r
            );
        }
    }
}
