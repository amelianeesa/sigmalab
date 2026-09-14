<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('roles')
            ->where('roles_id', 3)
            ->update(['nama_role' => 'HR', 'updated_at' => now()]);

        DB::table('roles')->insert([
            'roles_id' => 7,
            'nama_role' => 'GA',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => NULL
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')
            ->where('roles_id', 3)
            ->update(['nama_role' => 'HR & GA', 'updated_at' => now()]);

        DB::table('roles')
            ->where('roles_id', 7)
            ->delete();
    }
};
