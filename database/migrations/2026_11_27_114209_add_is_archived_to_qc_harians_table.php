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
        Schema::table('qc_harians', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('draft_group_id');
        });
    }

    public function down(): void
    {
        Schema::table('qc_harians', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });
    }
};
