<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'roles', 'modul', 'personil', 'parameter_uji', 'barang', 'alat', 
            'kompetensi_personil', 'users', 'kegiatan', 'kegiatan_alat', 
            'kegiatan_personil', 'hasil_uji', 'riwayat_kalibrasi', 'riwayat_pelatihan', 
            'riwayat_tindak_lanjut', 'permintaan_pengadaan', 'transaksi_barang', 
            'laporan_stok_bulanan', 'hak_akses', 'item_pemeliharaan', 'log_pemeliharaan'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'roles', 'modul', 'personil', 'parameter_uji', 'barang', 'alat', 
            'kompetensi_personil', 'users', 'kegiatan', 'kegiatan_alat', 
            'kegiatan_personil', 'hasil_uji', 'riwayat_kalibrasi', 'riwayat_pelatihan', 
            'riwayat_tindak_lanjut', 'permintaan_pengadaan', 'transaksi_barang', 
            'laporan_stok_bulanan', 'hak_akses', 'item_pemeliharaan', 'log_pemeliharaan'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
