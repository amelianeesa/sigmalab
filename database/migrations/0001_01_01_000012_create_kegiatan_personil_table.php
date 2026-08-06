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
        Schema::create('kegiatan_personil', function (Blueprint $table) {
            $table->unsignedBigInteger('kegiatan_id');
            $table->unsignedBigInteger('personil_id');
            $table->string('peran', 50);

            $table->primary(['kegiatan_id', 'personil_id']);
            $table->foreign('kegiatan_id')->references('kegiatan_id')->on('kegiatan')->cascadeOnDelete();
            $table->foreign('personil_id')->references('personil_id')->on('personil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_personil');
    }
};
