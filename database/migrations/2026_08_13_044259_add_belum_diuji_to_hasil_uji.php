<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE hasil_uji MODIFY COLUMN status_berketerimaan ENUM('inlier', 'outlier', 'pending', 'belum_diuji') NOT NULL DEFAULT 'belum_diuji'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE hasil_uji MODIFY COLUMN status_berketerimaan ENUM('inlier', 'outlier', 'pending') NOT NULL");
    }
};
