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
            $table->decimal('lcl', 12, 4)->nullable()->after('batas_atas');
            $table->decimal('uwl_bawah', 12, 4)->nullable()->after('lcl');
            $table->decimal('mean', 12, 4)->nullable()->after('uwl_bawah');
            $table->decimal('uwl_atas', 12, 4)->nullable()->after('mean');
            $table->decimal('ucl', 12, 4)->nullable()->after('uwl_atas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->dropColumn(['lcl', 'uwl_bawah', 'mean', 'uwl_atas', 'ucl']);
        });
    }
};
