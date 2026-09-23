<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_verifikasi_teknis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_katalog_id')->constrained('crm_katalog')->cascadeOnDelete();
            $table->unsignedBigInteger('parameter_uji_id');
            $table->foreign('parameter_uji_id')->references('parameter_uji_id')->on('parameter_uji');
            $table->unsignedBigInteger('analis_id')->nullable();
            $table->foreign('analis_id')->references('personil_id')->on('personil')->nullOnDelete();

            $table->float('nilai_d1')->nullable();
            $table->float('nilai_d2')->nullable();
            $table->float('nilai_akhir')->nullable();
            $table->float('cert_value');
            $table->float('cert_u');
            $table->float('batas_bawah');
            $table->float('batas_atas');
            $table->enum('status_evaluasi', ['inlier', 'outlier'])->nullable();
            $table->json('data_mentah')->nullable();
            $table->timestamp('tanggal_uji')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_verifikasi_teknis');
    }
};