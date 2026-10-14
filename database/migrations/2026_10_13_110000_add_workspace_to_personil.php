<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('personil', 'workspace')) {
            Schema::table('personil', function (Blueprint $table) {
                $table->string('workspace', 10)->default('LAB')->index();
            });
        }

        if (Schema::hasColumn('personil', 'tipe_personil')) {
            DB::table('personil')->whereRaw("LOWER(tipe_personil) LIKE 'non%'")->update(['workspace' => 'NON_LAB']);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('personil', 'workspace')) {
            Schema::table('personil', function (Blueprint $table) {
                $table->dropColumn('workspace');
            });
        }
    }
};