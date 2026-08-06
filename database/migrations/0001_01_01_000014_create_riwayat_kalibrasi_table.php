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
        Schema::create('riwayat_kalibrasi', function (Blueprint $table) {
            $table->id('riwayat_kalibrasi_id');
            $table->unsignedBigInteger('alat_id');
            $table->enum('jenis_kalibrasi', ['internal', 'eksternal']);
            $table->string('no_sertifikat', 100)->nullable();
            $table->string('interval_kalibrasi', 50)->nullable();
            $table->date('tgl_kalibrasi');
            $table->date('tgl_akhir');
            $table->string('lembaga_kalibrasi', 150)->nullable();
            $table->string('range_kapasitas', 100)->nullable();
            $table->string('faktor_koreksi', 100)->nullable();
            $table->enum('signifikan', ['ya', 'tidak'])->default('tidak');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('alat_id')->references('alat_id')->on('alat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_kalibrasi');
    }
};
