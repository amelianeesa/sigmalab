<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AftKalibrasi extends Model
{
    protected $table = 'aft_kalibrasi';

    protected $fillable = [
        'nama_kalibrasi',
        'tanggal_kalibrasi',
        'data_points',
        'is_active',
    ];

    protected $casts = [
        'data_points' => 'array',
        'is_active' => 'boolean',
        'tanggal_kalibrasi' => 'date',
    ];
}
