<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibraryAccessSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('modul')->updateOrInsert(
            ['kode_modul' => 'library_manage'],
            ['nama_modul' => 'Library Digital']
        );

        $moduleId = DB::table('modul')->where('kode_modul', 'library_manage')->value('modul_id');

        foreach (DB::table('roles')->get(['roles_id', 'nama_role']) as $role) {
            $level = in_array($role->nama_role, ['Admin Lab', 'Analis Lab', 'Admin Aplikasi'], true)
                ? 'tambah_ubah'
                : 'lihat';

            DB::table('hak_akses')->updateOrInsert(
                ['role_id' => $role->roles_id, 'modul_id' => $moduleId],
                ['level_akses' => $level, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
