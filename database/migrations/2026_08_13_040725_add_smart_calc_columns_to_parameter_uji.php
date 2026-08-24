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
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->string('kategori_parameter', 50)->nullable()->after('nama_parameter');
            $table->json('variabel_input')->nullable()->after('rumus_kalkulasi');
            $table->json('dependensi_parameter')->nullable()->after('variabel_input')->comment('Array nama_parameter yang dibutuhkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->dropColumn(['kategori_parameter', 'variabel_input', 'dependensi_parameter']);
        });
    }
};
