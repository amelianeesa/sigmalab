<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_pemeliharaan', function (Blueprint $table) {
            $table->id('item_id');
            $table->foreignId('alat_id')->constrained('alat', 'alat_id')->onDelete('cascade');
            $table->integer('nomor_urut');
            $table->string('nama_pemeliharaan');
            $table->timestamps();
        });

        Schema::create('log_pemeliharaan', function (Blueprint $table) {
            $table->id('log_pemeliharaan_id');
            $table->foreignId('alat_id')->constrained('alat', 'alat_id')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('item_pemeliharaan', 'item_id')->onDelete('cascade');
            $table->date('tanggal');
            $table->boolean('status')->default(false);
            $table->text('tindakan')->nullable();
            $table->string('petugas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_pemeliharaan');
        Schema::dropIfExists('item_pemeliharaan');
    }
};
