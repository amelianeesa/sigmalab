<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcCrm extends Model
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
        'cert_value' => 'float',
        'cert_u' => 'float',
        'data_mentah' => 'array',
    ];

    public function crmKatalog()
    {
        return $this->belongsTo(CrmKatalog::class, 'crm_katalog_id');
    }

    public function parameterUji()
    {
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id', 'parameter_uji_id');
    }

    public function analis()
    {
        return $this->belongsTo(Personil::class, 'analis_id', 'id'); // Adjust PK if necessary
    }
}
