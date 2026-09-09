<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataHomogenitas extends BaseModel
{
    use HasFactory;

    protected $table = 'data_homogenitas';
    protected $primaryKey = 'data_homogenitas_id';
    
    const UPDATED_AT = null;

    protected $fillable = [
        'sampel_inhouse_parameter_id',
        'nomor_sampel',
        'nomor_botol_fisik',
        'urutan_instrumen_d1',
        'urutan_instrumen_d2',
        'data_mentah',
        'nilai_d1',
        'nilai_d2',
        'mean_sampel',
    ];

    protected $casts = [
        'data_mentah' => 'array',
    ];

    public function parameter()
    {
        return $this->belongsTo(SampelInhouseParameter::class, 'sampel_inhouse_parameter_id', 'id');
    }
}
