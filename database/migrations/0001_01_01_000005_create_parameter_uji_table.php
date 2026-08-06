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
        Schema::create('parameter_uji', function (Blueprint $table) {
            $table->id('parameter_uji_id');
            $table->string('nama_parameter', 50);
            $table->string('satuan', 20);
            $table->decimal('nilai_acuan', 12, 4);
            $table->decimal('batas_bawah', 12, 4);
            $table->decimal('batas_atas', 12, 4);
            $table->string('metode_kriteria', 50)->nullable();
            $table->text('rumus_kalkulasi')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameter_uji');
    }
};
