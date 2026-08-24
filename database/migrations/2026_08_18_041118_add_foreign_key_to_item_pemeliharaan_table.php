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
        Schema::table('item_pemeliharaan', function (Blueprint $table) {
            $table->foreign('alat_id')
                  ->references('alat_id')
                  ->on('alat')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_pemeliharaan', function (Blueprint $table) {
            $table->dropForeign(['alat_id']);
        });
    }
};
