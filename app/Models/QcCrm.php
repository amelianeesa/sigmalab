<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class QcCrm extends Model
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
        'cert_value' => 'float',
        'cert_u' => 'float',
        'data_mentah' => 'array',
    ];

    public function crmKatalog()
    {
        return $this->belongsTo(CrmKatalog::class, 'crm_katalog_id');
    }

    public function parameterUji()
    {
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id', 'parameter_uji_id');
    }

    public function analis()
    {
        return $this->belongsTo(Personil::class, 'analis_id', 'personil_id'); 
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'tanggal_uji',
                'no_lembar_kerja',
                'nilai_d1',
                'nilai_d2',
                'nilai_db_1',
                'nilai_db_2',
                'nilai_akhir',
                'cert_value',
                'cert_u',
                'status_evaluasi',
                'catatan',
                'data_mentah',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {
                $param = $this->parameterUji->nama_parameter ?? 'Parameter';
                $lot = $this->crmKatalog->nomor_lot ?? '';
                
                if ($this->status_evaluasi === 'draft') {
                    return $eventName === 'updated'
                        ? "Memperbarui draft pengujian CRM {$param} (Lot: {$lot})"
                        : "Menyimpan draft pengujian CRM {$param} (Lot: {$lot})";
                }
                
                $eval = strtoupper($this->status_evaluasi ?? 'SELESAI');
                return "Pengujian QC CRM {$param} (Lot: {$lot}) - Evaluasi: {$eval}";
            });
    }
}
