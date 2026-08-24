<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibraryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nama_kategori' => 'Prosedur', 'slug' => 'prosedur', 'deskripsi' => 'Dokumen prosedur kerja dan panduan operasional.', 'is_active' => true],
            ['nama_kategori' => 'Formulir', 'slug' => 'formulir', 'deskripsi' => 'Formulir dan template dokumen pendukung.', 'is_active' => true],
            ['nama_kategori' => 'Instruksi Kerja', 'slug' => 'instruksi-kerja', 'deskripsi' => 'Instruksi kerja dan SOP teknis.', 'is_active' => true],
            ['nama_kategori' => 'Dokumen Internal', 'slug' => 'dokumen-internal', 'deskripsi' => 'Dokumen internal laboratorium.', 'is_active' => true],
            ['nama_kategori' => 'Dokumen Eksternal', 'slug' => 'dokumen-eksternal', 'deskripsi' => 'Dokumen eksternal yang menjadi referensi laboratorium.', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            DB::table('library_categories')->updateOrInsert(
                ['slug' => $category['slug']],
                [
                    'nama_kategori' => $category['nama_kategori'],
                    'deskripsi' => $category['deskripsi'],
                    'is_active' => $category['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
