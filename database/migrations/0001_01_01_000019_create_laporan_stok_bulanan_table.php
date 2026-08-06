<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_stok_bulanan', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->unsignedBigInteger('barang_id');
            $table->string('periode', 7);
            $table->decimal('saldo_awal', 12, 4)->default(0.0000);
            $table->decimal('saldo_akhir', 12, 4)->default(0.0000);
            $table->decimal('harga_rata_rata', 12, 4)->default(0.0000);
            $table->decimal('nilai', 14, 2)->default(0.00);
            $table->enum('status', ['draft', 'disahkan'])->default('draft');
            $table->unsignedBigInteger('disiapkan_oleh');
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->date('tgl_approval')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('barang_id')->references('barang_id')->on('barang');
            $table->foreign('disiapkan_oleh')->references('users_id')->on('users');
            $table->foreign('disetujui_oleh')->references('users_id')->on('users')->nullOnDelete();
            $table->unique(['barang_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_stok_bulanan');
    }
};
