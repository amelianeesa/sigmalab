<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kompetensi_personil', function (Blueprint $table) {
            $table->timestamp('reminder_terakhir_dikirim')->nullable()->after('tanggal_berakhir');
        });
    }

    public function down(): void
    {
        Schema::table('kompetensi_personil', function (Blueprint $table) {
            $table->dropColumn('reminder_terakhir_dikirim');
        });
    }
};