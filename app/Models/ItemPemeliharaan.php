<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPemeliharaan extends Model
{
    protected $table = 'item_pemeliharaan';
    protected $primaryKey = 'item_id';
    protected $fillable = ['alat_id', 'nomor_urut', 'nama_pemeliharaan'];

    public function logs()
    {
        return $this->hasMany(LogPemeliharaan::class, 'item_id', 'item_id');
    }
}