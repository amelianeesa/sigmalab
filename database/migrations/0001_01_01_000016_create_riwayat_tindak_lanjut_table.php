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
        Schema::create('riwayat_tindak_lanjut', function (Blueprint $table) {
            $table->id('riwayat_tindak_lanjut_id');
            $table->unsignedBigInteger('hasil_uji_id');
            $table->enum('status_tindak_lanjut', ['belum_ditindaklanjuti', 'dalam_investigasi', 'selesai'])->default('belum_ditindaklanjuti');
            $table->text('catatan_investigasi')->nullable();
            $table->unsignedBigInteger('ditindaklanjuti_oleh');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('hasil_uji_id')->references('hasil_uji_id')->on('hasil_uji');
            $table->foreign('ditindaklanjuti_oleh')->references('users_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_tindak_lanjut');
    }
};
