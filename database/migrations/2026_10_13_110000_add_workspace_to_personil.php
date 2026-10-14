<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personil', function (Blueprint $table) {
            $table->string('workspace', 10)->default('LAB')->index();
        });

        // Jika sebelumnya ada personil yang sudah ditandai non-lab lewat kolom tipe_personil, ikut dipindahkan.
        DB::table('personil')->whereRaw("LOWER(tipe_personil) LIKE 'non%'")->update(['workspace' => 'NON_LAB']);
    }

    public function down(): void
    {
        Schema::table('personil', function (Blueprint $table) {
            $table->dropColumn('workspace');
        });
    }
};
