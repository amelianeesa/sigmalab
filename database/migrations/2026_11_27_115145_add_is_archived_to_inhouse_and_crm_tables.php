<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
    {
        Schema::table('sampel_inhouse', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false);
        });
        Schema::table('crm_katalog', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false);
        });
        Schema::table('qc_crms', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('sampel_inhouse', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });
        Schema::table('crm_katalog', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });
        Schema::table('qc_crms', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });
    }
};
