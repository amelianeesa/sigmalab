<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengadaanLog extends Model
{
    protected $table = 'permintaan_pengadaan_log';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'permintaan_id',
        'tahap',
        'aksi',
        'users_id',
        'dicatat_pada',
    ];

    protected $casts = [
        'dicatat_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }

    public function pengadaan()
    {
        return $this->belongsTo(PermintaanPengadaan::class, 'permintaan_id', 'permintaan_id');
    }
}