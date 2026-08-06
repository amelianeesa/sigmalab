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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id('kegiatan_id');
            $table->enum('jenis_kegiatan', ['pengujian', 'kalibrasi']);
            $table->string('kode_sampel', 50)->nullable();
            $table->date('tanggal_kegiatan');
            $table->enum('status_kegiatan', ['draft', 'berjalan', 'selesai', 'dibatalkan'])->default('draft');
            $table->unsignedBigInteger('dibuat_oleh');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('dibuat_oleh')->references('users_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
