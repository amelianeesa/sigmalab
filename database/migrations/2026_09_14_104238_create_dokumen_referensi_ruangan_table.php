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
        Schema::create('dokumen_referensi_ruangans', function (Blueprint $table) {
            $table->id('dokumen_id');
            $table->unsignedBigInteger('alat_id');
            $table->string('nama_ruangan');
            $table->string('bulan');
            $table->year('tahun');
            $table->string('file_path');
            $table->string('nama_file_asli')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_referensi_ruangan');
    }
};
