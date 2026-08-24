<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

class ParameterUji extends Model
{
    use SoftDeletes;
    use HasFactory, LogsActivity;

    protected $table = 'parameter_uji';
    protected $primaryKey = 'parameter_uji_id';

    protected $fillable = [
        'nama_parameter',
        'satuan',
        'nilai_acuan',
        'batas_bawah',
        'batas_atas',
        'lcl',
        'uwl_bawah',
        'mean',
        'uwl_atas',
        'ucl',
        'metode_kriteria',
        'rumus_kalkulasi',
        'status_aktif',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            // ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Data parameter uji telah di-{$eventName}");
    }
}

