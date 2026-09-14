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
            $table->string('paraf_1')->nullable()->change();
            $table->string('paraf_2')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_ruangan', function (Blueprint $table) {
            $table->integer('paraf_1')->nullable()->change();
            $table->integer('paraf_2')->nullable()->change();
        });
    }
};
