<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermintaanPengadaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'permintaan_pengadaan';
    protected $primaryKey = 'permintaan_id';

    protected $fillable = [
        'barang_id',
        'jumlah_diminta',
        'target_hari',
        'alasan',
        'foto',
        'status',
        'diajukan_oleh',
        'disetujui_oleh',
        'tanggal_pengajuan',
        'tanggal_keputusan',
        'catatan_approval',
        'nama_penerima',
        'foto_diterima',
        'waktu_diterima',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_keputusan' => 'datetime',
        'waktu_diterima' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    public function pemohon()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh', 'users_id');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'users_id');
    }
}