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
        Schema::table('qc_harians', function (Blueprint $table) {
            if (!Schema::hasColumn('qc_harians', 'data_mentah')) {
                $table->json('data_mentah')->nullable()->after('status_evaluasi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qc_harians', function (Blueprint $table) {
            $table->dropColumn('data_mentah');
        });
    }
};
