<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiPersonil extends Model
{
    use HasFactory;

    protected $table = 'kompetensi_personil'; 
    protected $primaryKey = 'kompetensi_personil_id';
    
    protected $fillable = [
        'personil_id',
        'jenis_sertifikasi',
        'no_sertifikasi',
        'tanggal_terbit',
        'tanggal_berakhir',
        'file_sertifikat',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'tanggal_berakhir' => 'date',
    ];

    public function personil()
    {
        return $this->belongsTo(Personil::class, 'personil_id', 'personil_id');
    }
}
