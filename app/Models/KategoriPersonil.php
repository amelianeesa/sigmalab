<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriPersonil extends Model
{
    use SoftDeletes;

    protected $table = 'kategori_personil';
    protected $primaryKey = 'kategori_personil_id';

    protected $fillable = [
        'kode',
        'nama_kategori',
        'deskripsi',
    ];

    public static function options(): array
    {
        return static::orderBy('nama_kategori')->pluck('nama_kategori', 'kode')->toArray();
    }
}
