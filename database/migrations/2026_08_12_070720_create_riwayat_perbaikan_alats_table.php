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
        Schema::create('riwayat_perbaikan_alat', function (Blueprint $table) {
            $table->id('riwayat_perbaikan_id');
            $table->unsignedBigInteger('alat_id');
            $table->date('tanggal_rusak');
            $table->unsignedBigInteger('dilaporkan_oleh');
            $table->text('deskripsi_kerusakan');
            $table->enum('status_perbaikan', ['Belum Diperbaiki', 'Dalam Perbaikan', 'Selesai', 'Tidak Bisa Diperbaiki'])->default('Belum Diperbaiki');
            $table->text('tindakan_perbaikan')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->unsignedBigInteger('diverifikasi_oleh')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('alat_id')->references('alat_id')->on('alat')->onDelete('cascade');
            $table->foreign('dilaporkan_oleh')->references('users_id')->on('users');
            $table->foreign('diverifikasi_oleh')->references('users_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_perbaikan_alat');
    }
};
