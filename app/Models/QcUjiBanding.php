<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcUjiBanding extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    protected $casts = [
        'draft_data' => 'array',
        'tanggal_terima' => 'date',
        'tanggal_uji' => 'date',
    ];

    public function parameters()
    {
        return $this->hasMany(QcUjiBandingParameter::class, 'qc_uji_banding_id');
    }
}
