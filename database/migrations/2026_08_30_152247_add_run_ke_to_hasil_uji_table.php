<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->integer('run_ke')->default(1)->after('jenis_kontrol');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->dropColumn('run_ke');
        });
    }
};
