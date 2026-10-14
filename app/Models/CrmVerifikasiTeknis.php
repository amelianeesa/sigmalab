<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmVerifikasiTeknis extends Model
{
    protected $table = 'crm_verifikasi_teknis';
    protected $guarded = ['id'];

    protected $casts = [
        'nilai_d1' => 'float',
        'nilai_d2' => 'float',
        'nilai_akhir' => 'float',
        'cert_value' => 'float',
        'cert_u' => 'float',
        'batas_bawah' => 'float',
        'batas_atas' => 'float',
        'data_mentah' => 'array',
        'tanggal_uji' => 'datetime',
    ];

    public function katalog()
    {
        return $this->belongsTo(CrmKatalog::class, 'crm_katalog_id');
    }

    public function parameterUji()
    {
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id', 'parameter_uji_id');
    }

    public function analis()
    {
        return $this->belongsTo(Personil::class, 'analis_id', 'personil_id');
    }

    public function personils()
    {
        return $this->belongsToMany(Personil::class, 'crm_verif_teknis_personil', 'crm_verifikasi_teknis_id', 'personil_id')
                    ->withPivot('peran')
                    ->withTimestamps();
    }

    public function alats()
    {
        return $this->belongsToMany(Alat::class, 'crm_verif_teknis_alat', 'crm_verifikasi_teknis_id', 'alat_id')
                    ->withTimestamps();
    }

    public function bahans()
    {
        return $this->belongsToMany(Barang::class, 'crm_verif_teknis_bahan', 'crm_verifikasi_teknis_id', 'barang_id')
                    ->withPivot('qty')
                    ->withTimestamps();
    }
}