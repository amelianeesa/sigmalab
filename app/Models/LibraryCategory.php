<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryCategory extends Model
{
    use HasFactory;

    protected $table = 'library_categories';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
        'is_active',
    ];

    public function documents()
    {
        return $this->hasMany(LibraryDocument::class, 'category_id');
    }
}
