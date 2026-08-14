<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
// use Spatie\Activitylog\Traits\LogsActivity;


class RiwayatPerbaikanAlat extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'riwayat_perbaikan_alat';
    protected $primaryKey = 'riwayat_perbaikan_id';

    protected $fillable = [
        'alat_id',
        'tanggal_rusak',
        'dilaporkan_oleh',
        'deskripsi_kerusakan',
        'status_perbaikan',
        'tindakan_perbaikan',
        'tanggal_selesai',
        'diverifikasi_oleh',
    ];

    protected $casts = [
        'tanggal_rusak' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id');
    }

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'dilaporkan_oleh', 'users_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh', 'users_id');
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\Support\LogOptions
    {
        return \Spatie\Activitylog\Support\LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Data riwayat perbaikan alat telah di-{$eventName}");
    }
}
