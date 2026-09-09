<?php

namespace App\Models;

use App\Models\Concerns\LogsStandardActivity;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

class ParameterUji extends BaseModel
{
    use SoftDeletes;
    use HasFactory, LogsActivity;

    protected $table = 'parameter_uji';

    protected $fillable = [
        'nama_parameter',
        'satuan',
        'kategori_parameter',
                'nilai_acuan',
        'batas_bawah',
        'batas_atas',
        'lcl',
        'uwl_bawah',
        'mean',
        'uwl_atas',
        'ucl',
        'sd',

        'aturan_aktif',
        'metode_kriteria',
        'rumus_kalkulasi',
        'langkah_kalkulasi',
        'variabel_input',
        'dependensi_parameter',
        'status_aktif',
        'toleransi_duplo',
    ];

    protected $casts = [
        'aturan_aktif' => 'array',
        'variabel_input' => 'array',
        'dependensi_parameter' => 'array',
        'langkah_kalkulasi' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'parameter_uji_id';
    }

    public function hasilUji()
    {
        return $this->hasMany(HasilUji::class, 'parameter_uji_id', 'parameter_uji_id');
    }

    public function sudahDipakaiDiHasilUji(): bool
    {
        return $this->hasilUji()->exists();
    }

    public function hasInhouseLimits(): bool
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            // ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Data parameter uji telah di-{$eventName}");
        return !is_null($this->mean) && !is_null($this->sd);
    }

    public function sertifikatCrm()
    {
        return $this->hasMany(CrmSertifikat::class, 'parameter_uji_id', 'parameter_uji_id');
    }
}
