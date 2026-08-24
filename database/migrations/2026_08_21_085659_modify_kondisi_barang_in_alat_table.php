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
        Schema::table('alat', function (Blueprint $table) {
            $table->dropColumn('kondisi_barang');
        });

        Schema::table('alat', function (Blueprint $table) {
            $table->enum('kondisi_barang', ['baik', 'rusak', 'perbaikan'])->default('baik')->after('ukuran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropColumn('kondisi_barang');
        });

        Schema::table('alat', function (Blueprint $table) {
            $table->enum('kondisi_barang', ['baik', 'rusak'])->default('baik')->after('ukuran');
        });
    }
};
