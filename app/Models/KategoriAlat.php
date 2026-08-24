<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriAlat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_alat';
    protected $primaryKey = 'kategori_alat_id';
    protected $guarded = [];

    public function alat()
    {
        return $this->hasMany(Alat::class, 'kategori_alat_id', 'kategori_alat_id');
    }

    public function itemPemeliharaan()
    {
        return $this->hasMany(ItemPemeliharaan::class, 'kategori_alat_id', 'kategori_alat_id');
    }
}
