<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcUjiBandingParameter extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'data_mentah' => 'array',
        'nilai_d1' => 'float',
        'nilai_d2' => 'float',
        'nilai_akhir' => 'float',
        'target_vendor' => 'float',
        'z_score' => 'float',
    ];

    public function program()
    {
        return $this->belongsTo(QcUjiBanding::class, 'qc_uji_banding_id');
    }

    public function parameterUji()
    {
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id', 'parameter_uji_id');
    }

    public function analis()
    {
        return $this->belongsTo(Personil::class, 'analis_id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id');
    }
}
