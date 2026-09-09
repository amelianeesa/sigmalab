<?php

namespace App\Models;

use App\Models\Concerns\LogsStandardActivity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\RiwayatKalibrasi;
use App\Models\KegiatanAlat;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Alat extends BaseModel
{
    use SoftDeletes;
    use HasFactory, LogsActivity;

    protected $table = 'alat';

    protected $fillable = [
        'kode_alat',
        'nama_alat',
        'merk_tipe',
        'no_seri',
        'warna',
        'ukuran',
        'kondisi_barang',
        'status_barang',
        'unit_kerja_pemilik',
        'qr_dicetak_pada',
    ];

    public function riwayatKalibrasi()
    {
        return $this->hasMany(RiwayatKalibrasi::class, 'alat_id', 'alat_id');
    }

    public function kegiatanAlat()
    {
        return $this->hasMany(KegiatanAlat::class, 'alat_id', 'alat_id');
    }
    public function kategoriAlat()
    {
        return $this->belongsTo(KategoriAlat::class, 'kategori_alat_id', 'kategori_alat_id');
    }

    public function itemPemeliharaan()
    {
        return $this->hasManyThrough(ItemPemeliharaan::class, KategoriAlat::class, 'kategori_alat_id', 'kategori_alat_id', 'kategori_alat_id', 'kategori_alat_id');
    }

    public function riwayatPerbaikan()
    {
        return $this->hasMany(RiwayatPerbaikanAlat::class, 'alat_id', 'alat_id');
    }

    public function scopeFilterStatusKalibrasi($query, $status)
    {
        if (!$status) return $query;

        return $query->whereHas('riwayatKalibrasi', function ($q) use ($status) {
            $q->whereIn('kalibrasi_id', function ($sub) {
                $sub->selectRaw('MAX(kalibrasi_id)')
                    ->from('riwayat_kalibrasi')
                    ->whereNull('deleted_at')
                    ->groupBy('alat_id');
            });

            $sekarang = \Carbon\Carbon::now()->startOfDay();
            $batasBulanDepan = \Carbon\Carbon::now()->startOfDay()->addDays(30);

            if ($status == 'kedaluarsa') {
                $q->whereNotNull('tgl_akhir')->where('tgl_akhir', '<', $sekarang);
            } elseif ($status == 'segera') {
                $q->whereNotNull('tgl_akhir')->whereBetween('tgl_akhir', [$sekarang, $batasBulanDepan]);
            } elseif ($status == 'aktif') {
                $q->whereNotNull('tgl_akhir')->where('tgl_akhir', '>', $batasBulanDepan);
            }
        });
    }
}
