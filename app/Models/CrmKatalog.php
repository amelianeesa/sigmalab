<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmKatalog extends Model
{
    protected $table = 'crm_katalog';
    protected $fillable = ['nama_produk', 'nomor_lot', 'tanggal_expired', 'is_active'];
    protected $casts = ['tanggal_expired' => 'date', 'is_active' => 'boolean'];

    public function sertifikats()
    {
        return $this->hasMany(CrmSertifikat::class, 'crm_katalog_id');
    }
}