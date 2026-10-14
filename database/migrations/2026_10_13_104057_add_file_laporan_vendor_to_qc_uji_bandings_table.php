<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qc_uji_bandings', function (Blueprint $table) {
            // Path file di server (contoh: qc-uji-banding/laporan-vendor/xxx.pdf)
            $table->string('file_laporan_vendor')->nullable()->after('keterangan');
            // Nama asli file saat di-upload user (contoh: Laporan_Resmi_DQA_2026.pdf)
            $table->string('nama_file_vendor')->nullable()->after('file_laporan_vendor');
        });
    }

    public function down(): void
    {
        Schema::table('qc_uji_bandings', function (Blueprint $table) {
            $table->dropColumn(['file_laporan_vendor', 'nama_file_vendor']);
        });
    }
};