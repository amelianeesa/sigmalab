<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'alat' => 'Manajemen Alat',
            'barang' => 'Manajemen Barang',
            'pengadaan' => 'Pengadaan Barang',
            'parameter_uji' => 'Parameter Uji',
            'proses_hasil' => 'Proses & Hasil',
            'tindak_lanjut' => 'Tindak Lanjut',
            'sdm' => 'Sumber Daya Manusia',
            'reporting' => 'Reporting',
            'audit_log' => 'Audit Log',
            'manajemen_pengguna' => 'Manajemen Pengguna',
            'keamanan' => 'Keamanan',
            'library_manage' => 'Library Digital',
        ];

        foreach ($modules as $kode => $nama) {
            DB::table('modul')->updateOrInsert(
                ['kode_modul' => $kode],
                ['nama_modul' => $nama]
            );
        }
    }
}
