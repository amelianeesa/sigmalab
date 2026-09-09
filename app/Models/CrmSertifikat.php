<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmSertifikat extends Model
{
    protected $table = 'crm_sertifikat';
    protected $fillable = ['crm_katalog_id', 'parameter_uji_id', 'cert_value', 'cert_u'];

    public function katalog()
    {
        return $this->belongsTo(CrmKatalog::class, 'crm_katalog_id');
    }

    public function parameterUji()
    {
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id', 'parameter_uji_id');
    }
}