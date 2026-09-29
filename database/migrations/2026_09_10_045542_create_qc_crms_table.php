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
        Schema::create('qc_crms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_katalog_id')->constrained('crm_katalog')->onDelete('cascade');
            $table->foreignId('parameter_uji_id')->constrained('parameter_uji', 'parameter_uji_id')->onDelete('cascade');
            
            $table->date('tanggal_uji');
            $table->unsignedBigInteger('analis_id')->nullable();
            
            // Input Nilai (ADB)
            $table->decimal('nilai_d1', 10, 4)->nullable();
            $table->decimal('nilai_d2', 10, 4)->nullable();
            
            // Konversi DB (Jika perlu)
            $table->decimal('nilai_db_1', 10, 4)->nullable();
            $table->decimal('nilai_db_2', 10, 4)->nullable();
            
            // Final Rata-rata yang akan dibandingkan ke Sertifikat
            $table->decimal('nilai_akhir', 10, 4);
            
            // Snapshot Sertifikat saat pengujian dilakukan
            $table->decimal('cert_value', 10, 4);
            $table->decimal('cert_u', 10, 4);
            
            $table->enum('status_evaluasi', ['inlier', 'outlier'])->default('inlier');
            $table->json('data_mentah')->nullable(); // Simpan M1, M2, A, B, dll
            
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qc_crms');
    }
};
