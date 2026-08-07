<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HakAksesSeeder extends Seeder
{
    public function run(): void
    {
        
        $roles = DB::table('roles')->pluck('roles_id', 'nama_role');
        
        $modules = DB::table('modul')->pluck('modul_id', 'kode_modul');

        $matrix = [
            'Admin Lab' => [
                'sdm' => 'tambah_ubah',
                'alat' => 'full',
                'barang' => 'full',
                'parameter_uji' => 'full',
                'pengadaan' => 'tambah_ubah',
                'proses_hasil' => 'tambah_ubah',
                'tindak_lanjut' => 'tambah_ubah',
                'reporting' => 'lihat',
                'manajemen_pengguna' => 'tambah_ubah'
            ],
            'Koordinator Laboratorium' => [
                'parameter_uji' => 'full',
                'tindak_lanjut' => 'tambah_ubah',
                'proses_hasil' => 'lihat',
                'alat' => 'lihat',
                'barang' => 'lihat',
                'pengadaan' => 'tambah_ubah',
                'sdm' => 'lihat',
                'reporting' => 'lihat'
            ],
            'Analis' => [
                'proses_hasil' => 'tambah_ubah',
                'alat' => 'lihat',
                'barang' => 'lihat',
                'pengadaan' => 'tambah_ubah'
            ],
            'HR & GA' => [
                'sdm' => 'full'
            ],
            'Kabid Inspeksi dan Solusi Perdagangan' => [
                'reporting' => 'lihat',
                'tindak_lanjut' => 'lihat',
                'proses_hasil' => 'lihat',
                'audit_log' => 'lihat'
            ],
            'Kabid Dukungan Bisnis' => [
                'pengadaan' => 'tambah_ubah',
                'reporting' => 'lihat',
                'barang' => 'lihat',
                'audit_log' => 'lihat'
            ],
            'Admin Aplikasi' => [
                'alat' => 'full',
                'barang' => 'full',
                'pengadaan' => 'full',
                'parameter_uji' => 'full',
                'proses_hasil' => 'full',
                'tindak_lanjut' => 'full',
                'sdm' => 'full',
                'reporting' => 'full',
                'audit_log' => 'lihat',
                'manajemen_pengguna' => 'full',
                'keamanan' => 'full'
            ]
        ];

        foreach ($matrix as $roleName => $accesses) {
            if (!isset($roles[$roleName])) continue;
            
            $roleId = $roles[$roleName];
            
            foreach ($accesses as $moduleCode => $level) {
                if (!isset($modules[$moduleCode])) continue;
                
                $moduleId = $modules[$moduleCode];
                
                DB::table('hak_akses')->updateOrInsert(
                    ['role_id' => $roleId, 'modul_id' => $moduleId],
                    ['level_akses' => $level, 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
