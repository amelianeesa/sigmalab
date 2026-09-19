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
        Schema::create('qc_uji_banding_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qc_uji_banding_id')->constrained('qc_uji_bandings')->onDelete('cascade');
            $table->foreignId('parameter_uji_id')->constrained('parameter_uji', 'parameter_uji_id')->onDelete('cascade');
            $table->unsignedBigInteger('analis_id')->nullable();
            
            // Blind test result
            $table->decimal('nilai_d1', 10, 4)->nullable();
            $table->decimal('nilai_d2', 10, 4)->nullable();
            $table->decimal('nilai_akhir', 10, 4);
            $table->json('data_mentah')->nullable();
            
            // Evaluasi Vendor (diisi belakangan)
            $table->decimal('target_vendor', 10, 4)->nullable();
            $table->decimal('z_score', 10, 4)->nullable();
            $table->enum('status_evaluasi', ['menunggu', 'inlier', 'warning', 'outlier'])->default('menunggu');
            
            // Investigasi / LKS
            $table->enum('status_investigasi', ['aman', 'menunggu_investigasi', 'selesai_investigasi'])->default('aman');
            $table->text('akar_masalah')->nullable();
            $table->text('tindakan_perbaikan')->nullable();
            $table->text('tindakan_pencegahan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qc_uji_banding_parameters');
    }
};
