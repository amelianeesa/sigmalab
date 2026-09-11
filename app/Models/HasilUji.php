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


class HasilUji extends BaseModel
{
    use SoftDeletes;
    use HasFactory, LogsActivity;

    protected $table = 'hasil_uji';
    const UPDATED_AT = null;

    protected $fillable = [
        'kegiatan_id',
        'parameter_uji_id',
        'nilai_hasil',
        'status_berketerimaan',
        'crm_katalog_id',
        'kode_aturan_dilanggar',
        'override_status',
        'override_kode',
        'keterangan_override',
        'z_score',
        'data_mentah',
        'diinput_oleh',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'data_mentah' => 'array',
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
            // ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Hasil uji lab telah di-{$eventName}");
    }


    public function scopePending($query) { return $query->where('status_berketerimaan',
        'crm_katalog_id', 'pending'); }
    public function scopeInlier($query) { return $query->where('status_berketerimaan',
        'crm_katalog_id', 'inlier'); }
    public function scopeOutlier($query) { return $query->where('status_berketerimaan',
        'crm_katalog_id', 'outlier'); }
    public function scopeGagalDuplo($query) { return $query->where('status_berketerimaan',
        'crm_katalog_id', 'gagal_duplo'); }
}
