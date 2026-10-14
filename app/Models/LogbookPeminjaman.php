<?php

namespace App\Models;

use App\Models\Concerns\BelongsToWorkspace;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class LogbookPeminjaman extends BaseModel
{
    use SoftDeletes, LogsActivity, BelongsToWorkspace;

    protected $table = 'logbook_peminjaman';

    protected $fillable = [
        'alat_id', 'tanggal', 'rencana_kembali', 'tanggal_kembali', 'pekerjaan', 'petugas', 'paraf', 'dibuat_oleh',
        'status', 'disetujui_oleh', 'tgl_keputusan', 'catatan_approval', 'dikoreksi_pada',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'rencana_kembali' => 'date',
        'tanggal_kembali' => 'date',
        'tgl_keputusan' => 'datetime',
        'dikoreksi_pada' => 'datetime',
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id')->withoutGlobalScopes()->withTrashed();
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'users_id');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'users_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        // Gambar paraf tidak dimasukkan ke audit trail agar log tidak membengkak.
        return LogOptions::defaults()
            ->logOnly(['alat_id', 'tanggal', 'rencana_kembali', 'tanggal_kembali', 'pekerjaan', 'petugas', 'status', 'disetujui_oleh', 'catatan_approval', 'dikoreksi_pada'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $e) => "Catatan logbook peminjaman telah di-{$e}");
    }
}