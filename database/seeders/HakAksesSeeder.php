<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HakAksesSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil pemetaan ID Role
        $roles = DB::table('roles')->pluck('roles_id', 'nama_role');
        // Ambil pemetaan ID Modul
        $modules = DB::table('modul')->pluck('modul_id', 'kode_modul');

        $matrix = [
            'Analis Lab' => [
                'sdm' => 'tambah_ubah',
                'alat' => 'full',
                'barang' => 'full',
                'parameter_uji' => 'full',
                'pengadaan' => 'tambah_ubah',
                'proses_hasil' => 'tambah_ubah',
                'tindak_lanjut' => 'tambah_ubah',
                'reporting' => 'lihat',
                'audit_log' => 'lihat',
                'library_manage' => 'tambah_ubah'
            ],
            'Koordinator Laboratorium' => [
                'parameter_uji' => 'full',
                'tindak_lanjut' => 'tambah_ubah',
                'proses_hasil' => 'lihat',
                'alat' => 'lihat',
                'barang' => 'lihat',
                'pengadaan' => 'tambah_ubah',
                'sdm' => 'lihat',
                'reporting' => 'lihat',
                'audit_log' => 'lihat',
                'library_manage' => 'lihat'
            ],
            'HR & GA' => [
                'sdm' => 'full',
                'pengadaan' => 'tambah_ubah',
                'audit_log' => 'lihat',
                'library_manage' => 'lihat'
            ],
            'Kabid Inspeksi dan Solusi Perdagangan' => [
                'sdm' => 'lihat',
                'alat' => 'lihat',
                'barang' => 'lihat',
                'parameter_uji' => 'lihat',
                'pengadaan' => 'lihat',
                'proses_hasil' => 'lihat',
                'tindak_lanjut' => 'lihat',
                'reporting' => 'lihat',
                'audit_log' => 'lihat',
                'library_manage' => 'lihat'
            ],
            'Kabid Dukungan Bisnis' => [
                'sdm' => 'lihat',
                'alat' => 'lihat',
                'barang' => 'lihat',
                'parameter_uji' => 'lihat',
                'pengadaan' => 'lihat',
                'proses_hasil' => 'lihat',
                'tindak_lanjut' => 'lihat',
                'reporting' => 'lihat',
                'audit_log' => 'lihat',
                'library_manage' => 'lihat'
            ],
            'Admin Aplikasi' => [
                'manajemen_pengguna' => 'full',
                'keamanan' => 'full',
                'sdm' => 'lihat',
                'alat' => 'lihat',
                'barang' => 'lihat',
                'parameter_uji' => 'lihat',
                'pengadaan' => 'lihat',
                'proses_hasil' => 'lihat',
                'tindak_lanjut' => 'lihat',
                'reporting' => 'lihat',
                'audit_log' => 'lihat',
                'library_manage' => 'full'
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
