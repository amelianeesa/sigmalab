<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatKalibrasi extends BaseModel
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'riwayat_kalibrasi';
    public $timestamps = false;

    protected $fillable = [
        'alat_id',
        'jenis_kalibrasi',
        'no_sertifikat',
        'interval_kalibrasi',
        'tgl_kalibrasi',
        'tgl_akhir',
        'lembaga_kalibrasi',
        'range_kapasitas',
        'faktor_koreksi',
        'catatan_evaluasi',
        'signifikan',
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id'}
