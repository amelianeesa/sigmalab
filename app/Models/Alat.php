<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RiwayatKalibrasi;
use App\Models\KegiatanAlat;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Alat extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'alat';
    protected $primaryKey = 'alat_id';

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Data alat telah di-{$eventName}");
    }
    public function itemPemeliharaan()
    {
        return $this->hasMany(ItemPemeliharaan::class, 'alat_id', 'alat_id')->orderBy('nomor_urut');
    }
}




