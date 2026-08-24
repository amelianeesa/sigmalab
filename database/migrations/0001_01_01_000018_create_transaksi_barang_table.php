<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_barang', function (Blueprint $table) {
            $table->id('transaksi_id');
            $table->unsignedBigInteger('barang_id');
            $table->unsignedBigInteger('kegiatan_id')->nullable();
            $table->decimal('jumlah_penerimaan', 12, 4)->default(0.0000);
            $table->decimal('jumlah_pengeluaran', 12, 4)->default(0.0000);
            $table->decimal('harga', 12, 4);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('barang_id')->references('barang_id')->on('barang')->cascadeOnDelete();
            $table->foreign('kegiatan_id')->references('kegiatan_id')->on('kegiatan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_barang');
    }
};
