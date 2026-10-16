<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class QcUjiBanding extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = ['id'];
    
    protected $casts = [
        'draft_data' => 'array',
        'tanggal_terima' => 'date',
        'tanggal_uji' => 'date',
    ];

    public function parameters()
    {
        return $this->hasMany(QcUjiBandingParameter::class, 'qc_uji_banding_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nama_program',
                'penyelenggara',
                'tanggal_terima',
                'tanggal_uji',
                'kode_sampel',
                'status',
                'keterangan',
                'nama_file_vendor',
                'draft_data',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {
                $nama = $this->nama_program ?? 'Program Uji Banding';
                $kode = $this->kode_sampel ? " (Kode: {$this->kode_sampel})" : '';

                if ($this->status === 'draft') {
                    return $eventName === 'created'
                        ? "Menyimpan draft baru Uji Banding: {$nama}{$kode}"
                        : "Memperbarui draft Uji Banding: {$nama}{$kode}";
                }

                if ($this->wasChanged('nama_file_vendor') && $this->nama_file_vendor) {
                    return "Mengunggah laporan evaluasi vendor untuk Uji Banding: {$nama}{$kode}";
                }

                if ($eventName === 'created') {
                    return "Membuat data pengujian Uji Banding: {$nama}{$kode}";
                }

                if ($eventName === 'updated') {
                    return "Memperbarui informasi program Uji Banding: {$nama}{$kode}";
                }

                return "Aktivitas program Uji Banding: {$nama}{$kode}";
            });
    }
}