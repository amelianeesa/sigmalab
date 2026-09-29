<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parameter_toleransi', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel parameter uji (sesuaikan nama tabel referensinya jika berbeda)
            $table->unsignedBigInteger('parameter_uji_id');
$table->foreign('parameter_uji_id')->references('parameter_uji_id')->on('parameter_uji')->onDelete('cascade');
            
            $table->string('sub_parameter')->nullable()->comment('Untuk Tipe B spt CHN: Carbon, Hydrogen');
            $table->string('kategori_label')->nullable()->comment('Contoh: Coal, Bituminus, Method A');
            $table->string('metode')->nullable()->comment('Contoh: ASTM (db)');
            
            $table->string('range_label')->nullable()->comment('Tampilan teks: 1.0 - 21.9%');
            $table->double('range_min')->nullable()->comment('Angka desimal batas bawah');
            $table->double('range_max')->nullable()->comment('Angka desimal batas atas');
            
            $table->string('unit')->nullable()->comment('%, Kcal/Kg, Celcius');
            
            $table->string('formula_r')->comment('Bisa angka statis (0.08) atau rumus dinamis (0.09 + 0.01 * X)');
            $table->string('formula_R_besar')->comment('Batas Reproducibility');
            
            $table->text('catatan')->nullable()->comment('Keterangan tambahan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parameter_toleransi');
    }
};