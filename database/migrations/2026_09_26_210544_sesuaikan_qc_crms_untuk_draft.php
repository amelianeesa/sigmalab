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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `qc_crms` MODIFY COLUMN `status_evaluasi` ENUM('inlier', 'outlier', 'draft') NULL");
        
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `qc_crms` MODIFY COLUMN `nilai_akhir` DECIMAL(10, 4) NULL");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `qc_crms` MODIFY COLUMN `cert_value` DECIMAL(10, 4) NULL");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `qc_crms` MODIFY COLUMN `cert_u` DECIMAL(10, 4) NULL");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `qc_crms` MODIFY COLUMN `tanggal_uji` DATE NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
