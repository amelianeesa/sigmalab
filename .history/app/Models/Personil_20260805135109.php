<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KompetensiPersonil; // Memanggil model KompetensiPersonil yang ada di folder

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
        // Menghubungkan ke model KompetensiPersonil
        return $this->hasMany(KompetensiPersonil::class, 'personil_id', 'personil_id');
    }
}