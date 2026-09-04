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
        Schema::table('titik_kalibrasi', function (Blueprint $table) {
            $table->date('tanggal_kalibrasi')->nullable()->after('standard_reading');
            $table->date('tanggal_expired')->nullable()->after('tanggal_kalibrasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('titik_kalibrasi', function (Blueprint $table) {
            $table->dropColumn(['tanggal_kalibrasi', 'tanggal_expired']);
        });
    }
};
