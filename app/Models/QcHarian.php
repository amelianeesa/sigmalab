<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class QcHarian extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_uji' => 'date',
        'nilai_d1' => 'float',
        'nilai_d2' => 'float',
        'nilai_db_1' => 'float',
        'nilai_db_2' => 'float',
        'nilai_akhir' => 'float',
        'mean_acuan' => 'float',
        'sd_acuan' => 'float',
        'data_mentah' => 'array',
    ];

    public function sampelInhouse()
    {
        return $this->belongsTo(SampelInhouse::class, 'sampel_inhouse_id');
    }

    public function parameterUji()
    {
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id');
    }

    public function analis()
    {
        return $this->belongsTo(User::class, 'analis_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'tanggal_uji',
                'nilai_d1',
                'nilai_d2',
                'nilai_db_1',
                'nilai_db_2',
                'nilai_akhir',
                'status_evaluasi',
                'status_pengujian',
                'pelanggaran_rule',
                'data_mentah'
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {
                $param = $this->parameterUji->nama_parameter ?? '';
                $status = $this->status_pengujian ?? 'pengujian';

                if ($status === 'draft') {
                    return $eventName === 'updated'
                        ? "Memperbarui draft pengujian {$param}"
                        : "Menyimpan draft baru pengujian {$param}";
                }
                
                return "Data pengujian QC Harian {$param} telah di-{$eventName}";
            });
    }
}
