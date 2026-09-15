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
        Schema::table('evaluasi_kalibrasi', function (Blueprint $table) {
            $table->string('keputusan', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluasi_kalibrasi', function (Blueprint $table) {
            $table->enum('keputusan', ['Layak', 'Tidak Layak', 'Ya'])->change();
        });
    }
};
