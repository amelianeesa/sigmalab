<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Role Non-Lab boleh melihat daftar & mengajukan pengadaan barang/bahan.
 * Menu "Cek Pengadaan" di sidebar muncul otomatis lewat hasModulAccess('pengadaan').
 */
return new class extends Migration
{
    public function up(): void
    {
        $roleId  = DB::table('roles')->where('nama_role', 'Non-Lab')->value('roles_id');
        $modulId = DB::table('modul')->where('kode_modul', 'pengadaan')->value('modul_id');

        if ($roleId && $modulId) {
            $now = now();
            $ada = DB::table('hak_akses')->where('role_id', $roleId)->where('modul_id', $modulId)->first();

            if ($ada) {
                DB::table('hak_akses')->where('id', $ada->id)
                    ->update(['level_akses' => 'tambah_ubah', 'deleted_at' => null, 'updated_at' => $now]);
            } else {
                DB::table('hak_akses')->insert([
                    'role_id' => $roleId, 'modul_id' => $modulId, 'level_akses' => 'tambah_ubah',
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }

        Cache::forget('hak_akses_matrix');
    }

    public function down(): void
    {
        $roleId  = DB::table('roles')->where('nama_role', 'Non-Lab')->value('roles_id');
        $modulId = DB::table('modul')->where('kode_modul', 'pengadaan')->value('modul_id');

        if ($roleId && $modulId) {
            DB::table('hak_akses')->where('role_id', $roleId)->where('modul_id', $modulId)->delete();
        }

        Cache::forget('hak_akses_matrix');
    }
};
