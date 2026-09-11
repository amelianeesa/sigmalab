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
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->string('toleransi_duplo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->decimal('toleransi_duplo', 15, 4)->nullable()->change();
        });
    }
};
