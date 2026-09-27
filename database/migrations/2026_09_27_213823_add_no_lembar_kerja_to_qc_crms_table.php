<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::table('qc_crms', function (Blueprint $table) {
            // Menambahkan kolom tepat setelah tanggal_uji
            $table->string('no_lembar_kerja')->nullable()->after('tanggal_uji');
        });
    }

    public function down()
    {
        Schema::table('qc_crms', function (Blueprint $table) {
            $table->dropColumn('no_lembar_kerja');
        });
    }
};
