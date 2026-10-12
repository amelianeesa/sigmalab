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
        return !is_null($this->mean) && !is_null($this->sd);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Data parameter uji telah di-{$eventName}");
    }

    public function sertifikatCrm()
    {
        return $this->hasMany(CrmSertifikat::class, 'parameter_uji_id', 'parameter_uji_id');
    }

    public function toleransis()
    {
        return $this->hasMany(ParameterToleransi::class, 'parameter_uji_id', 'parameter_uji_id');
    }

    public function getNamaLengkapAttribute(): string
    {
        $code = strtoupper(trim(str_replace(' - %', '', $this->nama_parameter)));
        $map = [
            'IM'      => 'Inherent Moisture',
            'ASH'     => 'Ash Content (Kadar Abu)',
            'VM'      => 'Volatile Matter (Zat Terbang)',
            'FC'      => 'Fixed Carbon (Karbon Tertambat)',
            'TS'      => 'Total Sulfur',
            'CV'      => 'Gross Calorific Value (Nilai Kalor)',
            'GCV'     => 'Gross Calorific Value (Nilai Kalor)',
            'NCV'     => 'Net Calorific Value',
            'TM'      => 'Total Moisture (Kadar Air Total)',
            'RM'      => 'Residual Moisture',
            'C'       => 'Carbon (C)',
            'H'       => 'Hydrogen (H)',
            'N'       => 'Nitrogen (N)',
            'HGI'     => 'Hardgrove Grindability Index',
            'AFT-IDT' => 'Ash Fusion Temperature (IDT)',
            'AFT-ST'  => 'Ash Fusion Temperature (ST)',
            'AFT-HT'  => 'Ash Fusion Temperature (HT)',
            'AFT-FT'  => 'Ash Fusion Temperature (FT)',
        ];
        return $map[$code] ?? $this->nama_parameter;
    }
}
