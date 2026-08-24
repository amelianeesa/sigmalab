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
        Schema::table('kompetensi_personil', function (Blueprint $table) {
            $table->foreignId('parameter_uji_id')->nullable()->after('jenis_sertifikasi')->constrained('parameter_uji', 'parameter_uji_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kompetensi_personil', function (Blueprint $table) {
            $table->dropForeign(['parameter_uji_id']);
            $table->dropColumn('parameter_uji_id');
        });
    }
};
