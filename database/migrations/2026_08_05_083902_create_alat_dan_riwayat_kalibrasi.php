<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alat', function (Blueprint $table) {
            $table->id('alat_id');
            $table->string('kode_alat', 50)->unique();
            $table->string('nama_alat', 100);
            $table->string('merk_tipe', 100)->nullable();
            $table->string('no_seri', 100)->nullable();
            $table->string('warna', 30)->nullable();
            $table->string('ukuran', 50)->nullable();
            $table->enum('kondisi_barang', ['baik', 'rusak'])->default('baik');
            $table->enum('status_barang', ['terpakai', 'idle'])->default('idle');
            $table->string('unit_kerja_pemilik', 100)->nullable();
            $table->timestamp('qr_dicetak_pada')->nullable();
            $table->timestamps();
        });

        Schema::create('riwayat_kalibrasi', function (Blueprint $table) {
            $table->id('riwayat_kalibrasi_id');
            $table->foreignId('alat_id')->constrained('alat', 'alat_id')->onDelete('cascade');
            $table->enum('jenis_kalibrasi', ['internal', 'eksternal']);
            $table->string('no_sertifikat', 100)->nullable();
            $table->string('interval_kalibrasi', 50)->nullable();
            $table->date('tgl_kalibrasi');
            $table->date('tgl_akhir');
            $table->string('lembaga_kalibrasi', 150)->nullable();
            $table->string('range_kapasitas', 100)->nullable();
            $table->string('faktor_koreksi', 100)->nullable();
            $table->enum('signifikan', ['ya', 'tidak'])->default('tidak');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kalibrasi');
        Schema::dropIfExists('alat');
    }
};