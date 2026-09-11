<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SampelInhouse extends BaseModel
{
    use SoftDeletes, HasFactory;

    protected $table = 'sampel_inhouse';

    protected $fillable = [
        'nama_sampel',
        'jenis_batubara',
        'metode_acuan',
        'kode_batch',
        'jumlah_botol',
        'nomor_awal_botol',
        'data_pemilihan_sampel',
        'data_equilibrium',
        'bobot_konstan_tercapai',
        'catatan_preparasi',
        'tanggal_pemilihan',
        'tanggal_preparasi',
        'tanggal_penetapan_target',
        'dibuat_oleh',
        'dipreparasi_oleh',
        'urutan_acak_instrumen',
        'status'
    ];

    protected $casts = [
        'data_pemilihan_sampel' => 'array',
        'data_equilibrium' => 'array',
        'urutan_acak_instrumen' => 'array',
        'bobot_konstan_tercapai' => 'boolean',
        'tanggal_pemilihan' => 'date',
        'tanggal_preparasi' => 'date',
        'tanggal_penetapan_target' => 'date',
    ];

    public function parameters()
    {
        return $this->hasMany(SampelInhouseParameter::class, 'sampel_inhouse_id', 'sampel_inhouse_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'users_id');
    }

    public function preparator()
    {
        return $this->belongsTo(User::class, 'dipreparasi_oleh', 'users_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pemilihan_sampel' => 'Pemilihan Sampel',
            'preparasi' => 'Preparasi Sampel',
            'uji_homogenitas' => 'Uji Homogenitas',
            'gagal_homogenitas' => 'Gagal Homogenitas',
            'penetapan_target' => 'Penetapan Nilai Target',
            'uji_stabilitas' => 'Uji Stabilitas',
            'gagal_stabilitas' => 'Gagal Stabilitas',
            'siap_digunakan' => 'Siap Digunakan (Menunggu Aktivasi)',
            'aktif' => 'Aktif (Sedang Digunakan)',
            'habis' => 'Habis',
            'kadaluarsa' => 'Kadaluarsa',
            'investigasi' => 'Investigasi',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pemilihan_sampel' => 'info',
            'preparasi' => 'primary',
            'uji_homogenitas', 'penetapan_target', 'uji_stabilitas' => 'warning',
            'gagal_homogenitas', 'gagal_stabilitas', 'investigasi' => 'danger',
            'siap_digunakan' => 'info',
            'aktif' => 'success',
            'habis', 'kadaluarsa' => 'dark',
            default => 'secondary',
        };
    }
}
