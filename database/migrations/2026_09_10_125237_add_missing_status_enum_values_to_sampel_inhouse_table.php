<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE `sampel_inhouse`
            MODIFY `status` ENUM(
                'pemilihan_sampel',
                'preparasi',
                'uji_homogenitas',
                'gagal_homogenitas',
                'penetapan_target',
                'uji_stabilitas',
                'gagal_stabilitas',
                'siap_digunakan',
                'aktif',
                'kadaluarsa',
                'habis',
                'investigasi'
            ) NOT NULL DEFAULT 'pemilihan_sampel'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE `sampel_inhouse`
            MODIFY `status` ENUM(
                'pemilihan_sampel',
                'preparasi',
                'uji_homogenitas',
                'gagal_homogenitas',
                'penetapan_target',
                'uji_stabilitas',
                'gagal_stabilitas',
                'aktif',
                'habis',
                'investigasi'
            ) NOT NULL DEFAULT 'pemilihan_sampel'
        ");
    }
};