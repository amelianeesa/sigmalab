<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPenetapanTarget extends Model
{
    use HasFactory;

    protected $table = 'data_penetapan_target';
    
    protected $primaryKey = 'id';
    
    const UPDATED_AT = null;

    protected $fillable = [
        'sampel_inhouse_parameter_id',
        'nomor_pengujian',
        'nilai_hasil',
        'analis_id',
        'tanggal_pengujian'
    ];

    protected $casts = [
        'tanggal_pengujian' => 'date',
    ];

    public function parameter()
    {
        return $this->belongsTo(SampelInhouseParameter::class, 'sampel_inhouse_parameter_id', 'id');
    }

    public function analis()
    {
        return $this->belongsTo(User::class, 'analis_id', 'users_id');
    }
}
