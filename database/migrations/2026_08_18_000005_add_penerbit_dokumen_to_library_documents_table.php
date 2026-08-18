<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('library_documents', function (Blueprint $table) {
            $table->string('penerbit_dokumen')->nullable()->after('kode_dokumen');
        });
    }

    public function down(): void
    {
        Schema::table('library_documents', function (Blueprint $table) {
            $table->dropColumn('penerbit_dokumen');
        });
    }
};
