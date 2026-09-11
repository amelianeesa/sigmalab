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
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->decimal('toleransi_duplo', 8, 4)->nullable()->after('status_aktif')->comment('Batas toleransi maksimal untuk selisih hasil pengujian duplo. NULL jika tidak ada toleransi.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->dropColumn('toleransi_duplo');
        });
    }
};
