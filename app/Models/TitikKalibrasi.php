<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TitikKalibrasi extends Model
{
    protected $table = 'titik_kalibrasi';
    protected $primaryKey = 'titik_kalibrasi_id';
    protected $guarded = ['titik_kalibrasi_id'];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id');
    }
}