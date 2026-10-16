<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class QcUjiBandingParameter extends Model
{
    use HasFactory, LogsActivity;
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'data_mentah' => 'array',
        'nilai_d1' => 'float',
        'nilai_d2' => 'float',
        'nilai_akhir' => 'float',
        'target_vendor' => 'float',
        'sdpa' => 'float',
        'z_score' => 'float',
    ];

    public function program()
    {
        return $this->belongsTo(QcUjiBanding::class, 'qc_uji_banding_id');
    }

    public function parameterUji()
    {
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id', 'parameter_uji_id');
    }

    public function analis()
    {
        return $this->belongsTo(Personil::class, 'analis_id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nilai_d1',
                'nilai_d2',
                'nilai_akhir',
                'target_vendor',
                'sdpa',
                'z_score',
                'status_evaluasi',
                'status_investigasi',
                'akar_masalah',
                'tindakan_perbaikan',
                'tindakan_pencegahan',
                'metode_uji',
                'uncertainty_lab',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {
                $param = $this->parameterUji->nama_parameter ?? 'Parameter';
                $program = $this->program->nama_program ?? 'Program Uji Banding';

                if ($eventName === 'created') {
                    return "Menyimpan data pengujian parameter {$param} pada {$program}";
                }

                // Jika yang disimpan/diubah adalah data LKS / CAPA
                if ($this->wasChanged('status_investigasi') && $this->status_investigasi === 'selesai_investigasi') {
                    return "Menyimpan Lembar Ketidaksesuaian (LKS/CAPA) {$param} pada {$program}";
                }

                // Jika yang diinput/diubah adalah hasil evaluasi vendor & Z-Score
                if ($this->wasChanged('target_vendor') || $this->wasChanged('z_score') || $this->wasChanged('status_evaluasi')) {
                    $z = $this->z_score !== null ? " (Z = {$this->z_score})" : '';
                    $eval = strtoupper($this->status_evaluasi ?? 'EVALUASI');
                    return "Evaluasi Z-Score parameter {$param} pada {$program}: {$eval}{$z}";
                }

                if ($eventName === 'updated') {
                    return "Memperbarui hasil pengujian parameter {$param} pada {$program}";
                }

                if ($eventName === 'deleted') {
                    return "Menghapus pengujian parameter {$param} pada {$program}";
                }

                return "Aktivitas pengujian {$param} pada {$program}";
            });
    }
}