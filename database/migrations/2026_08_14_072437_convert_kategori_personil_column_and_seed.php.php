<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE personil MODIFY kategori_personil VARCHAR(50) NULL");

        $now = now();

        DB::table('kategori_personil')->insert([
            ['kode' => 'chemist', 'nama_kategori' => 'Chemist', 'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'analist', 'nama_kategori' => 'Analist', 'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'preparator', 'nama_kategori' => 'Preparator', 'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'sampler', 'nama_kategori' => 'Sampler', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE personil MODIFY kategori_personil ENUM('chemist','analist','preparator','sampler') NULL");
        DB::table('kategori_personil')->whereIn('kode', ['chemist', 'analist', 'preparator', 'sampler'])->delete();
    }
};
