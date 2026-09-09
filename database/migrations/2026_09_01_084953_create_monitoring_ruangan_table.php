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
        Schema::create('monitoring_ruangan', function (Blueprint $table) {
            $table->id('monitoring_id');
            $table->string('bulan', 20);
            $table->year('tahun');
            $table->string('nama_ruangan', 100);
            $table->string('persyaratan_suhu', 50)->nullable();
            $table->string('persyaratan_kelembaban', 50)->nullable();

            $table->unsignedBigInteger('alat_id');
            $table->foreign('alat_id')->references('alat_id')->on('alat')->onDelete('cascade');
            
            $table->unsignedTinyInteger('tanggal');
            
            // Sesi 1 (pagi)
            $table->string('waktu_1', 20)->nullable();
            $table->decimal('suhu_pembacaan_1', 5, 2)->nullable();
            $table->decimal('suhu_terkoreksi_1', 5, 2)->nullable();
            $table->decimal('kelembaban_pembacaan_1', 5, 2)->nullable();
            $table->decimal('kelembaban_terkoreksi_1', 5, 2)->nullable();
            $table->boolean('paraf_1')->default(false);

            // Sesi 2 (sore)
            $table->string('waktu_2', 20)->nullable();
            $table->decimal('suhu_pembacaan_2', 5, 2)->nullable();
            $table->decimal('suhu_terkoreksi_2', 5, 2)->nullable();
            $table->decimal('kelembaban_pembacaan_2', 5, 2)->nullable();
            $table->decimal('kelembaban_terkoreksi_2', 5, 2)->nullable();
            $table->boolean('paraf_2')->default(false);

            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_ruangan');
    }
};
