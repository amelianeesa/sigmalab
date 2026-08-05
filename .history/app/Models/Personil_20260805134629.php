<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KompetensiModel; // <-- Pastikan baris ini ada di atas

class Personil extends Model
{
    use HasFactory;

    protected $table = 'personil';
    protected $primaryKey = 'personil_id';

    protected $fillable = [
        'nama',
        'jabatan',
        'unit_kerja',
        'no_induk',
        'file_cv',
        'status_aktif',
    ];

    public function kompetensi()
    {
        return $this->hasMany(KompetensiModel::class, 'personil_id', 'personil_id');
    }
}