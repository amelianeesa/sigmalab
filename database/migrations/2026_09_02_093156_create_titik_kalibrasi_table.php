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
        Schema::create('titik_kalibrasi', function (Blueprint $table) {
            $table->id('titik_kalibrasi_id'); // Menggunakan nama titik_kalibrasi_id sebagai Primary Key
            $table->unsignedBigInteger('alat_id'); 
            $table->string('kategori'); 
            $table->decimal('equipment_reading', 8, 2); 
            $table->decimal('standard_reading', 8, 2);  
            $table->timestamps();

            $table->foreign('alat_id')->references('alat_id')->on('alat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titik_kalibrasi');
    }
};
