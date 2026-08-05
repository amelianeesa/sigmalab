<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiPersonil extends Model
{
    use HasFactory;

    protected $table = 'kompetensi_personil'; // Sesuaikan dengan nama tabel di database Anda
    protected $primaryKey = 'kompetensi_id'; // Sesuaikan jika primary key berbeda
    
    protected $fillable = [
        'personil_id',
        'jenis_sertifikasi',
        'no_sertifikasi',
        'tanggal_terbit',
        'tanggal_berakhir',
    ];

    public function personil()
    {
        return $this->belongsTo(Personil::class, 'personil_id', 'personil_id');
    }
}