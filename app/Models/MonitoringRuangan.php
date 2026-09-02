<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringRuangan extends Model
{
    protected $table = 'monitoring_ruangan';
    protected $primaryKey = 'monitoring_id';
    protected $guarded = ['monitoring_id'];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }
}