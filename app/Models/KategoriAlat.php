<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriAlat extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_alat';
    protected $guarded = [];

    public function alat()
    {
        return $this->hasMany(Alat::class, 'kategori_alat_id', 'kategori_alat_id');
    }

    public function itemPemeliharaan()
    {
        return $this->hasMany(ItemPemeliharaan::class, 'kategori_alat_id', 'kategori_alat_id'}
