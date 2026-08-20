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
            if (!Schema::hasColumn('item_pemeliharaan', 'alat_id')) {
                $table->unsignedBigInteger('alat_id')->nullable()->after('item_id');
                $table->foreign('alat_id')->references('alat_id')->on('alat')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_pemeliharaan', function (Blueprint $table) {
            if (Schema::hasColumn('item_pemeliharaan', 'alat_id')) {
                $table->dropForeign(['alat_id']);
                $table->dropColumn('alat_id');
            }
        });
    }
};