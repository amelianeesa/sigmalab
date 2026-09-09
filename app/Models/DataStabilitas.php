<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataStabilitas extends BaseModel
{
    use HasFactory;

    protected $table = 'data_stabilitas';
    protected $primaryKey = 'data_stabilitas_id';
    
    const UPDATED_AT = null;

    protected $fillable = [
        'sampel_inhouse_parameter_id',
        'nomor_pengujian',
        'nomor_botol_fisik',
        'data_mentah',
        'nilai_d1',
        'nilai_d2',
        'mean_pengujian',
    ];

    protected $casts = [
        'data_mentah' => 'array',
    ];

    public function parameter()
    {
        return $this->belongsTo(SampelInhouseParameter::class, 'sampel_inhouse_parameter_id', 'id');
    }
}
