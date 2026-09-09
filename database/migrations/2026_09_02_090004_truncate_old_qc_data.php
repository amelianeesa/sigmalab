<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Disable FK checks for truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $tables = [
            'riwayat_tindak_lanjut',
            'hasil_uji',
            'kegiatan_alat',
            'kegiatan_personil',
            'transaksi_barang',
            'kegiatan'
        ];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        // Data cannot be restored
    }
};
