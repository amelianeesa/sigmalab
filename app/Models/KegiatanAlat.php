<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanAlat extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_alat';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kegiatan_id',
        'alat_id',
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'kegiatan_id');
    }
}