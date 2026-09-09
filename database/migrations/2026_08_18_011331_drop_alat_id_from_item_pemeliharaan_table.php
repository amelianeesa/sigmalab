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
        Schema::table('item_pemeliharaan', function (Blueprint $table) {
            if (Schema::hasColumn('item_pemeliharaan', 'alat_id')) {
                try {
                    $table->dropForeign(['alat_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('alat_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_pemeliharaan', function (Blueprint $table) {
            $table->unsignedBigInteger('alat_id')->nullable();
        });
    }
};
