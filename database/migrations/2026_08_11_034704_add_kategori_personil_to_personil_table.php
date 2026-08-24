<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom kategori_personil (chemist, analist, preparator, sampler)
     * ke tabel personil, ditaruh setelah kolom jabatan.
     */
    public function up(): void
    {
        Schema::table('personil', function (Blueprint $table) {
            $table->enum('kategori_personil', ['chemist', 'analist', 'preparator', 'sampler'])
                ->nullable()
                ->after('jabatan');
        });
    }

    public function down(): void
    {
        Schema::table('personil', function (Blueprint $table) {
            $table->dropColumn('kategori_personil');
        });
    }
};