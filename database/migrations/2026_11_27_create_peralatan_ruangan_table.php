<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peralatan_ruangan', function (Blueprint $table) {
            $table->id('peralatan_ruangan_id');
            $table->string('nama_ruangan', 150);
            $table->string('nama_peralatan', 150);
            $table->timestamps();

            $table->unique(['nama_ruangan', 'nama_peralatan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peralatan_ruangan');
    }
};