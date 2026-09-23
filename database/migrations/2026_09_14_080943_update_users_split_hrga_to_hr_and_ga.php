<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
        ->where('username', 'hrga_tester') 
        ->update([
            'username' => 'hr_tester',
            'email' => 'hr@test.com',
            'password' => Hash::make('password'), 
            'role_id' => 3, 
            'updated_at' => now()
        ]);

        DB::table('users')->updateOrInsert(
            ['username' => 'ga_tester'], 
            [
                'users_id' => 8, 
                'email' => 'ga@test.com',
                'password' => Hash::make('password'), 
                'role_id' => 7, 
                'status_aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
        ->where('username', 'hr_tester')
        ->update([
            'username' => 'hrga_tester',
            'email' => 'hrga@test.com',
            'updated_at' => now()
        ]);

        DB::table('users')
            ->where('username', 'ga_tester')
            ->delete();
    }
};
