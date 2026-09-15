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
        Schema::create('evaluasi_kalibrasi', function (Blueprint $table) {
            $table->id('evaluasi_id');
            $table->unsignedBigInteger('alat_id');
            $table->unsignedBigInteger('riwayat_kalibrasi_id')->nullable();
            $table->date('tanggal_evaluasi');
            $table->string('file_laporan')->nullable();
            $table->text('catatan_spesifikasi')->nullable();
            $table->enum('keputusan', ['idle', 'perbaikan', 'ganti_alat']);
            $table->unsignedBigInteger('dievaluasi_oleh')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('alat_id')->references('alat_id')->on('alat')->onDelete('cascade');
            $table->foreign('riwayat_kalibrasi_id')->references('riwayat_kalibrasi_id')->on('riwayat_kalibrasi')->onDelete('set null');
            $table->foreign('dievaluasi_oleh')->references('users_id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_kalibrasi');
    }
};
