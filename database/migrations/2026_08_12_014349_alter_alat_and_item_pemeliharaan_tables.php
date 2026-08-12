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
        Schema::table('alat', function (Blueprint $table) {
            $table->foreignId('kategori_alat_id')->nullable()->constrained('kategori_alat', 'kategori_alat_id')->onDelete('set null');
        });

        Schema::table('item_pemeliharaan', function (Blueprint $table) {
            $table->dropForeign(['alat_id']);
            $table->dropColumn('alat_id');
            $table->foreignId('kategori_alat_id')->nullable()->after('item_id')->constrained('kategori_alat', 'kategori_alat_id')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('item_pemeliharaan', function (Blueprint $table) {
            $table->dropForeign(['kategori_alat_id']);
            $table->dropColumn('kategori_alat_id');
            $table->foreignId('alat_id')->nullable()->constrained('alat', 'alat_id')->onDelete('cascade');
        });

        Schema::table('alat', function (Blueprint $table) {
            $table->dropForeign(['kategori_alat_id']);
            $table->dropColumn('kategori_alat_id');
        });
    }
};
