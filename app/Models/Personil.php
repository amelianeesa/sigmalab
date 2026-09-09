<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\KompetensiPersonil;

class Personil extends BaseModel
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'personil';

    protected $fillable = [
        'nama',
        'jabatan',
        'kategori_personil',
        'unit_kerja',
        'no_induk',
        'file_cv',
        'status_aktif',
    ];

    public function kompetensi()
    {
        return $this->hasMany(KompetensiPersonil::class, 'personil_id', 'personil_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'personil_id', 'personil_id');
    }

    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_personil', 'personil_id', 'kegiatan_id');
    }
}
