<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_kalibrasi', function (Blueprint $table) {
            $table->text('catatan_evaluasi')->nullable()->after('faktor_koreksi');
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_kalibrasi', function (Blueprint $table) {
            $table->dropColumn('catatan_evaluasi');
        });
    }
};