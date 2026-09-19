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
        Schema::create('qc_uji_bandings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program');
            $table->string('penyelenggara');
            $table->date('tanggal_terima');
            $table->date('tanggal_uji');
            $table->string('kode_sampel');
            
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qc_uji_bandings');
    }
};
