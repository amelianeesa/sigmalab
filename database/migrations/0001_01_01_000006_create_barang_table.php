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
        Schema::create('barang', function (Blueprint $table) {
            $table->id('barang_id');
            $table->string('nama_barang', 100);
            $table->string('satuan', 20);
            $table->string('kode_barang', 50);
            $table->decimal('minimal_stok', 12, 4)->default(0.0000);
            $table->enum('kondisi', ['baik', 'rusak'])->default('baik');
            $table->date('tgl_exp')->nullable();
            $table->decimal('harga_rata', 12, 4)->default(0.0000);
            $table->decimal('saldo_akhir', 12, 4)->default(0.0000);
            $table->timestamp('qr_dicetak_pada')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique('kode_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
