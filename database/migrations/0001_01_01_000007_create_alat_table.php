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
        Schema::create('alat', function (Blueprint $table) {
            $table->id('alat_id');
            $table->string('kode_alat', 50);
            $table->string('nama_alat', 100);
            $table->string('merk_tipe', 100)->nullable();
            $table->string('no_seri', 100)->nullable();
            $table->string('warna', 30)->nullable();
            $table->string('ukuran', 50)->nullable();
            $table->enum('kondisi_barang', ['baik', 'rusak'])->default('baik');
            $table->enum('status_barang', ['terpakai', 'idle'])->default('idle');
            $table->string('unit_kerja_pemilik', 100)->nullable();
            $table->timestamp('qr_dicetak_pada')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique('kode_alat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
