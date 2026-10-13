<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook_peminjaman', function (Blueprint $table) {
            $table->id('logbook_peminjaman_id');
            $table->unsignedBigInteger('alat_id')->index();
            $table->date('tanggal');
            $table->string('pekerjaan', 255);
            $table->string('petugas', 100);
            $table->longText('paraf')->nullable();            // gambar tanda tangan (PNG data URI)
            $table->unsignedBigInteger('dibuat_oleh');
            $table->string('status', 15)->default('diajukan')->index(); // diajukan | disetujui | ditolak
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->timestamp('tgl_keputusan')->nullable();
            $table->string('catatan_approval', 500)->nullable();
            $table->string('workspace', 10)->default('NON_LAB')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('alat_id')->references('alat_id')->on('alat');
            $table->foreign('dibuat_oleh')->references('users_id')->on('users');
            $table->foreign('disetujui_oleh')->references('users_id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_peminjaman');
    }
};
