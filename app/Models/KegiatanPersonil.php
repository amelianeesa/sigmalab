<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KegiatanPersonil extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'kegiatan_personil';

    protected $fillable = [
        'kegiatan_id',
        'personil_id',
        'peran',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'kegiatan_id');
    }

    public function personil()
    {
        return $this->belongsTo(Personil::class, 'personil_id', 'personil_id');
    }
}

