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
        Schema::create('kegiatan_alat', function (Blueprint $table) {
            $table->unsignedBigInteger('kegiatan_id');
            $table->unsignedBigInteger('alat_id');

            $table->primary(['kegiatan_id', 'alat_id']);
            $table->foreign('kegiatan_id')->references('kegiatan_id')->on('kegiatan')->cascadeOnDelete();
            $table->foreign('alat_id')->references('alat_id')->on('alat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_alat');
    }
};
