<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampelInhouseParameter extends Model
{
    use HasFactory;

    protected $table = 'sampel_inhouse_parameter';
    
    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'sampel_inhouse_id',
        'parameter_uji_id',
        'status_parameter',
        'mean_global',
        'sd_global',
        'f_hitung',
        'f_tabel',
        'mean_target',
        'sd_target',
        'mean_stabilitas',
        'sd_stabilitas',
        't_hitung',
        't_tabel',
        'tanggal_homogenitas',
        'tanggal_stabilitas'
    ];

    protected $casts = [
        'tanggal_homogenitas' => 'date',
        'tanggal_stabilitas' => 'date',
    ];

    public function sampelInhouse()
    {
        return $this->belongsTo(SampelInhouse::class, 'sampel_inhouse_id', 'sampel_inhouse_id');
    }

    public function parameterUji()
    {
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id', 'parameter_uji_id');
    }

    public function dataHomogenitas()
    {
        return $this->hasMany(DataHomogenitas::class, 'sampel_inhouse_parameter_id', 'id')->orderBy('nomor_sampel');
    }

    public function dataStabilitas()
    {
        return $this->hasMany(DataStabilitas::class, 'sampel_inhouse_parameter_id', 'id')->orderBy('nomor_pengujian');
    }

    public function dataPenetapanTarget()
    {
        return $this->hasMany(DataPenetapanTarget::class, 'sampel_inhouse_parameter_id', 'id')->orderBy('nomor_pengujian');
    }
}
