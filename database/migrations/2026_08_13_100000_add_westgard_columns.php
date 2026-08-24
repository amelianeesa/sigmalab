<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom-kolom yang dibutuhkan oleh Westgard Rules Engine.
     */
    public function up(): void
    {
        // Tambah kolom SD dan konfigurasi aturan aktif ke parameter_uji
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->decimal('sd', 12, 4)->nullable()->after('ucl')->comment('Standar Deviasi acuan untuk Westgard');
            $table->json('aturan_aktif')->nullable()->after('sd')->comment('Daftar aturan Westgard yang aktif, misal: ["1-3s","2-2s","R-4s","4-1s","10x"]');
        });

        // Tambah kolom kode aturan dilanggar dan z-score ke hasil_uji
        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->string('kode_aturan_dilanggar', 10)->nullable()->after('status_berketerimaan')->comment('Kode aturan Westgard yang terpicu: 1-2s, 1-3s, 2-2s, R-4s, 4-1s, 10x');
            $table->decimal('z_score', 8, 4)->nullable()->after('kode_aturan_dilanggar')->comment('Posisi nilai dalam satuan SD dari mean');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->dropColumn(['sd', 'aturan_aktif']);
        });

        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->dropColumn(['kode_aturan_dilanggar', 'z_score']);
        });
    }
};
