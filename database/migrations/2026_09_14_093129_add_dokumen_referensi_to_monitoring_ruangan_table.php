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
        Schema::table('monitoring_ruangan', function (Blueprint $table) {
            $table->string('dokumen_referensi')->nullable()->after('persyaratan_kelembaban');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_ruangan', function (Blueprint $table) {
            $table->dropColumn('dokumen_referensi');
        });
    }
};
