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
        Schema::create('master_rumuses', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rumus')->unique();
            $table->string('nama_rumus');
            $table->string('rumus_teks');
            $table->text('keterangan_variabel')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_rumuses');
    }
};
