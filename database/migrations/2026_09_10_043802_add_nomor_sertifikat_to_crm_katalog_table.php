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
        Schema::table('crm_katalog', function (Blueprint $table) {
            $table->string('nomor_sertifikat', 100)->nullable()->after('nomor_lot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_katalog', function (Blueprint $table) {
            $table->dropColumn('nomor_sertifikat');
        });
    }
};
