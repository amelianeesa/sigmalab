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
        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->json('data_mentah')->nullable()->after('nilai_hasil');
            $table->decimal('nilai_hasil', 12, 4)->nullable()->change();
        });
        
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE hasil_uji MODIFY COLUMN status_berketerimaan ENUM('inlier', 'outlier', 'pending') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->dropColumn('data_mentah');
            $table->decimal('nilai_hasil', 12, 4)->nullable(false)->change();
        });
        
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE hasil_uji MODIFY COLUMN status_berketerimaan ENUM('inlier', 'outlier') NOT NULL");
    }
};
