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
        Schema::create('riwayat_pelatihan', function (Blueprint $table) {
            $table->id('riwayat_pelatihan_id');
            $table->unsignedBigInteger('personil_id');
            $table->string('nama_pelatihan', 150);
            $table->string('penyelenggara', 150)->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('file_sertifikat', 255)->nullable();
            $table->enum('status_pelaksanaan', ['direncanakan', 'berlangsung', 'selesai'])->default('direncanakan');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('personil_id')->references('personil_id')->on('personil')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pelatihan');
    }
};
