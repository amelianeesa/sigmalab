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
        Schema::create('kompetensi_personil', function (Blueprint $table) {
            $table->id('kompetensi_personil_id');
            $table->unsignedBigInteger('personil_id');
            $table->string('jenis_sertifikasi', 100);
            $table->string('no_sertifikasi', 100)->nullable();
            $table->string('file_sertifikat', 255)->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->date('tanggal_berakhir')->nullable();
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
        Schema::dropIfExists('kompetensi_personil');
    }
};
