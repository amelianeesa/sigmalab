<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KompetensiPersonil extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'kompetensi_personil'; 
    protected $primaryKey = 'kompetensi_personil_id';
    
    protected $fillable = [
        'personil_id',
        'parameter_uji_id',
        'jenis_sertifikasi',
        'no_sertifikasi',
        'tanggal_terbit',
        'tanggal_berakhir',
        'file_sertifikat',
        'reminder_terkirim',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'tanggal_berakhir' => 'date',
    ];

    public function personil()
    {
        return $this->belongsTo(Personil::class, 'personil_id', 'personil_id');
    }

    public function parameterUji()
    {
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id', 'parameter_uji_id');
    }

    protected static function booted()
    {
        static::updating(function (KompetensiPersonil $item) {
            if ($item->isDirty('tanggal_berakhir')) {
                $item->reminder_terkirim = false;
            }
        });
    }
}