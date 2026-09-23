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
        Schema::table('monitoring_ruangan', function (Blueprint $table) {
            $table->string('status', 50)->nullable();
            if (Schema::hasColumn('monitoring_ruangan', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_ruangan', function (Blueprint $table) {
            $table->string('keterangan', 255)->nullable();
            $table->dropColumn('status');
        });
    }
};
