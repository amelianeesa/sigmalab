<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcHarian extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_uji' => 'date',
        'nilai_d1' => 'float',
        'nilai_d2' => 'float',
        'nilai_db_1' => 'float',
        'nilai_db_2' => 'float',
        'nilai_akhir' => 'float',
        'mean_acuan' => 'float',
        'sd_acuan' => 'float',
        'data_mentah' => 'array',
    ];

    public function sampelInhouse()
    {
        return $this->belongsTo(SampelInhouse::class, 'sampel_inhouse_id');
    }

    public function parameterUji()
    {
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id');
    }

    public function analis()
    {
        return $this->belongsTo(User::class, 'analis_id');
    }
}
