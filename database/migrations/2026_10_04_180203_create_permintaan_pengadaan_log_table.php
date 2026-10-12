<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_pengadaan_log', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('permintaan_id');
            $table->unsignedTinyInteger('tahap');
            $table->string('aksi', 20);
            $table->unsignedBigInteger('users_id')->nullable();
            $table->dateTime('dicatat_pada');
            $table->unique(['permintaan_id', 'tahap']);
            $table->index('users_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_pengadaan_log');
    }
};