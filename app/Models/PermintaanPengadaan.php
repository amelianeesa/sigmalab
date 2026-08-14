<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class PermintaanPengadaan extends Model
{
    use SoftDeletes;
    use HasFactory, LogsActivity;

    protected $table = 'permintaan_pengadaan';
    protected $primaryKey = 'permintaan_id';

    protected $fillable = [
        'barang_id',
        'jumlah_diminta',
        'alasan',
        'foto',
        'status',
        'diajukan_oleh',
        'disetujui_oleh',
        'tanggal_pengajuan',
        'tanggal_keputusan',
        'catatan_approval',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_keputusan' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    public function pemohon()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh', 'users_id');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'users_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Permintaan pengadaan telah di-{$eventName}");
    }
}

