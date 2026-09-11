<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->unsignedBigInteger('sampel_inhouse_id')->nullable()->after('toleransi_duplo');
            $table->foreign('sampel_inhouse_id')->references('sampel_inhouse_id')->on('sampel_inhouse')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->dropForeign(['sampel_inhouse_id']);
            $table->dropColumn('sampel_inhouse_id');
        });
    }
};
