<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemPemeliharaan extends Model
{
    use SoftDeletes;
    protected $table = 'item_pemeliharaan';
    protected $primaryKey = 'item_id';
    protected $fillable = ['alat_id', 'nomor_urut', 'nama_pemeliharaan'];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id');
    }
    // public function kategoriAlat()
    // {
    //     return $this->belongsTo(KategoriAlat::class, 'kategori_alat_id', 'kategori_alat_id');
    // }

    public function logs()
    {
        return $this->hasMany(LogPemeliharaan::class, 'item_id', 'item_id');
    }
}