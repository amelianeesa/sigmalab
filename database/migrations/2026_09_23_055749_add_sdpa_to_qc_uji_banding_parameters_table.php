<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qc_uji_banding_parameters', function (Blueprint $table) {
            $table->float('sdpa')->nullable()->after('target_vendor');
        });
    }

    public function down(): void
    {
        Schema::table('qc_uji_banding_parameters', function (Blueprint $table) {
            $table->dropColumn('sdpa');
        });
    }
};