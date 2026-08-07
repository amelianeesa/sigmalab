<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class HasilUji extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'hasil_uji';
    protected $primaryKey = 'hasil_uji_id';
    public $timestamps = false;

    protected $fillable = [
        'kegiatan_id',
        'parameter_uji_id',
        'nilai_hasil',
        'status_berketerimaan',
        'diinput_oleh',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function parameterUji()
    {
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id', 'parameter_uji_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'kegiatan_id');
    }

    public function penginput()
    {
        return $this->belongsTo(User::class, 'diinput_oleh', 'users_id');
    }

    public function tindakLanjut()
    {
        return $this->hasMany(RiwayatTindakLanjut::class, 'hasil_uji_id', 'hasil_uji_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Hasil uji lab telah di-{$eventName}");
    }
}
