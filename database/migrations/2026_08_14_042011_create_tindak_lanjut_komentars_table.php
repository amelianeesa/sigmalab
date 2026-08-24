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
        Schema::create('tindak_lanjut_komentar', function (Blueprint $table) {
            $table->id('komentar_id');
            $table->unsignedBigInteger('riwayat_tindak_lanjut_id');
            $table->unsignedBigInteger('users_id');
            $table->text('komentar');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('riwayat_tindak_lanjut_id')->references('riwayat_tindak_lanjut_id')->on('riwayat_tindak_lanjut')->onDelete('cascade');
            $table->foreign('users_id')->references('users_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjut_komentar');
    }
};
