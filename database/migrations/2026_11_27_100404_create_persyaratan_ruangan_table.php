<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persyaratan_ruangan', function (Blueprint $table) {
            $table->id('persyaratan_id');
            $table->string('nama_ruangan')->unique();
            $table->decimal('suhu_min', 6, 2)->nullable();
            $table->decimal('suhu_max', 6, 2)->nullable();
            $table->decimal('kelembaban_min', 6, 2)->nullable();
            $table->decimal('kelembaban_max', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persyaratan_ruangan');
    }
};