<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_pengadaan', function (Blueprint $table) {
            $table->id('permintaan_id');
            $table->unsignedBigInteger('barang_id');
            $table->decimal('jumlah_diminta', 12, 4);
            $table->text('alasan')->nullable();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'diproses', 'selesai'])->default('diajukan');
            $table->unsignedBigInteger('diajukan_oleh');
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_keputusan')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('barang_id')->references('barang_id')->on('barang')->onDelete('cascade');
            $table->foreign('diajukan_oleh')->references('users_id')->on('users');
            $table->foreign('disetujui_oleh')->references('users_id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_pengadaan');
    }
};
