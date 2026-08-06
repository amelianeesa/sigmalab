<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    // Mengubah KompetensiPersonil menjadi KompetensiModel sesuai file yang sudah ada
    return $this->hasMany(KompetensiModel::class, 'personil_id', 'personil_id');
}
}