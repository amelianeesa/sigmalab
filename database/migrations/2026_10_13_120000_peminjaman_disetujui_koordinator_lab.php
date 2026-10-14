<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Persetujuan peminjaman alat dipegang Koordinator Laboratorium (bukan GA).
 * - Koordinator: hak akses peminjaman = full, dan workspace akun = ALL
 *   (agar bisa pindah ke Non-Lab untuk memproses pengajuan).
 * - GA dan Admin Aplikasi: hanya melihat.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $modulId = DB::table('modul')->where('kode_modul', 'peminjaman')->value('modul_id');
        $koorId = DB::table('roles')->where('nama_role', 'Koordinator Laboratorium')->value('roles_id');

        if (!$modulId || !$koorId) {
            return;
        }

        $set = function (int $roleId, string $level) use ($modulId, $now) {
            $ada = DB::table('hak_akses')->where('role_id', $roleId)->where('modul_id', $modulId)->exists();
            if ($ada) {
                DB::table('hak_akses')->where('role_id', $roleId)->where('modul_id', $modulId)
                    ->update(['level_akses' => $level, 'updated_at' => $now]);
            } else {
                DB::table('hak_akses')->insert([
                    'role_id' => $roleId, 'modul_id' => $modulId, 'level_akses' => $level,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        };

        $set($koorId, 'full');

        foreach (['GA', 'Admin Aplikasi'] as $namaRole) {
            $id = DB::table('roles')->where('nama_role', $namaRole)->value('roles_id');
            if ($id) {
                $set($id, 'lihat');
            }
        }

        DB::table('users')->where('role_id', $koorId)->update(['workspace' => 'ALL']);

        Cache::forget('hak_akses_matrix');
    }

    public function down(): void
    {
        // Sengaja kosong: mengembalikan hak akses lama tidak diperlukan.
    }
};
