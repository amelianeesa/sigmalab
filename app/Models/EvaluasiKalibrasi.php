<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluasiKalibrasi extends BaseModel
{
    use SoftDeletes, HasFactory;

    protected $table = 'evaluasi_kalibrasi';
    protected $primaryKey = 'evaluasi_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public function getRouteKeyName()
    {
        return 'evaluasi_id';
    }

    protected $casts = [
        'tanggal_evaluasi' => 'date',
    ];

    protected $fillable = [
        'alat_id',
        'riwayat_kalibrasi_id',
        'tanggal_evaluasi',
        'file_laporan',
        'catatan_spesifikasi',
        'keputusan',
        'dievaluasi_oleh',
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id');
    }

    public function riwayatKalibrasi()
    {
        return $this->belongsTo(RiwayatKalibrasi::class, 'riwayat_kalibrasi_id', 'riwayat_kalibrasi_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'dievaluasi_oleh', 'users_id');
    }
}