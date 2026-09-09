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
            $table->timestamp('limit_terakhir_dihitung')->nullable()->after('status_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->dropColumn('limit_terakhir_dihitung');
        });
    }
};
