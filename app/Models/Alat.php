<?php

namespace App\Models;

use App\Models\Concerns\LogsStandardActivity;
use App\Models\Concerns\BelongsToWorkspace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\RiwayatKalibrasi;
use App\Models\KegiatanAlat;
use App\Models\ItemPemeliharaan;
use App\Models\RiwayatPerbaikanAlat;
use App\Models\EvaluasiKalibrasi;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Alat extends BaseModel
{
    use SoftDeletes;
    use HasFactory, LogsActivity, BelongsToWorkspace;

    protected $table = 'alat';

    protected $fillable = [
        'kode_alat',
        'no_inventaris',
        'nama_alat',
        'merk_tipe',
        'no_seri',
        'warna',
        'ukuran',
        'kondisi_barang',
        'status_barang',
        'unit_kerja_pemilik',
        'qr_dicetak_pada',
        'parent_id',
        'dapat_dipinjam',
    ];

    protected $casts = [
        'dapat_dipinjam' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Alat::class, 'parent_id', 'alat_id');
    }

    public function subAlat()
    {
        return $this->hasMany(Alat::class, 'parent_id', 'alat_id');
    }

    public function riwayatKalibrasi()
    {
        return $this->hasMany(RiwayatKalibrasi::class, 'alat_id', 'alat_id');
    }

    public function kegiatanAlat()
    {
        return $this->hasMany(KegiatanAlat::class, 'alat_id', 'alat_id');
    }

    // public function kategoriAlat()
    // {
    //     return $this->belongsTo(KategoriAlat::class, 'kategori_alat_id', 'kategori_alat_id');
    // }

    public function itemPemeliharaan()
    {
        return $this->hasMany(ItemPemeliharaan::class, 'alat_id', 'alat_id');
    }

    public function riwayatPerbaikan()
    {
        return $this->hasMany(RiwayatPerbaikanAlat::class, 'alat_id', 'alat_id');
    }
    public function evaluasiKalibrasi()
    {
        return $this->hasMany(EvaluasiKalibrasi::class, 'alat_id', 'alat_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Data alat telah di-{$eventName}");
    }
}
