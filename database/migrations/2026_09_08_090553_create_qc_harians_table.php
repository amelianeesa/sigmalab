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
        Schema::create('qc_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sampel_inhouse_id')->constrained('sampel_inhouse', 'sampel_inhouse_id')->onDelete('cascade');
            $table->foreignId('parameter_uji_id')->constrained('parameter_uji', 'parameter_uji_id')->onDelete('cascade');
            
            // Kondisi Uji
            $table->date('tanggal_uji');
            $table->unsignedBigInteger('analis_id')->nullable();
            
            // Nilai Mentah (ADB)
            $table->decimal('nilai_d1', 10, 4)->nullable();
            $table->decimal('nilai_d2', 10, 4)->nullable();
            
            // Nilai Terkonversi (DB) - jika parameter menuntut DB
            $table->decimal('nilai_db_1', 10, 4)->nullable();
            $table->decimal('nilai_db_2', 10, 4)->nullable();
            
            // Nilai Rata-rata final yang dievaluasi
            $table->decimal('nilai_akhir', 10, 4);
            
            // Nilai Acuan saat itu (Snapshotted dari ParameterUji / Homogenitas)
            $table->decimal('mean_acuan', 10, 4);
            $table->decimal('sd_acuan', 10, 4);
            
            // Status Westgard
            $table->enum('status_evaluasi', ['inlier', 'warning', 'outlier'])->default('inlier');
            $table->string('pelanggaran_rule')->nullable(); // misal: "Rule 2_2s"
            
            // Penanganan OOC (Investigasi)
            $table->text('catatan_investigasi')->nullable();
            $table->enum('status_investigasi', ['aman', 'menunggu_investigasi', 'selesai_investigasi'])->default('aman');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qc_harians');
    }
};
