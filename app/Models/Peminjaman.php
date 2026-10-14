<?php

namespace App\Models;

use App\Models\Concerns\BelongsToWorkspace;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Peminjaman extends BaseModel
{
    use SoftDeletes, LogsActivity, BelongsToWorkspace;

    protected $table = 'peminjaman';

    protected $fillable = [
        'kode', 'peminjam_id', 'tujuan', 'tgl_pinjam', 'tgl_rencana_kembali',
        'tgl_kembali_aktual', 'status', 'disetujui_oleh', 'tgl_keputusan', 'catatan_approval',
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_rencana_kembali' => 'date',
        'tgl_kembali_aktual' => 'date',
        'tgl_keputusan' => 'datetime',
    ];

    public const STATUS_LABEL = [
        'diajukan' => ['Menunggu Persetujuan', 'warning'],
        'disetujui' => ['Disetujui', 'info'],
        'dipinjam' => ['Sedang Dipinjam', 'primary'],
        'dikembalikan' => ['Dikembalikan', 'success'],
        'ditolak' => ['Ditolak', 'danger'],
        'dibatalkan' => ['Dibatalkan', 'secondary'],
    ];

    public function peminjam()
    {
        return $this->belongsTo(User::class, 'peminjam_id', 'users_id');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'users_id');
    }

    public function items()
    {
        return $this->hasMany(PeminjamanItem::class, 'peminjaman_id', 'peminjaman_id');
    }

    public function getTerlambatAttribute(): bool
    {
        return $this->status === 'dipinjam' && $this->tgl_rencana_kembali->lt(now()->startOfDay());
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $e) => "Peminjaman alat telah di-{$e}");
    }
}
