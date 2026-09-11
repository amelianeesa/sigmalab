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


class Kegiatan extends BaseModel
{
    use SoftDeletes;
    use HasFactory, LogsActivity;

    protected $table = 'kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'metode_verifikasi',
        'kode_sampel',
        'tanggal_kegiatan',
        'status_kegiatan',
        'dibuat_oleh',
    ];

    public function pembuatKegiatan()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'users_id');
    }

    public function alatDigunakan()
    {
        return $this->belongsToMany(Alat::class, 'kegiatan_alat', 'kegiatan_id', 'alat_id', 'kegiatan_id', 'alat_id');
    }

    public function personilTerlibat()
    {
        return $this->belongsToMany(Personil::class, 'kegiatan_personil', 'kegiatan_id', 'personil_id', 'kegiatan_id', 'personil_id')
                    ->withPivot('peran');
    }

    public function hasilUji()
    {
        return $this->hasMany(HasilUji::class, 'kegiatan_id', 'kegiatan_id');
    }

    public function transaksiBarang()
    {
        return $this->hasMany(TransaksiBarang::class, 'kegiatan_id', 'kegiatan_id');
    }


    public function scopeBerjalan($query) { return $query->where('status_kegiatan', 'berjalan'); }
    public function scopeDraft($query) { return $query->where('status_kegiatan', 'draft'); }
    public function scopeSelesai($query) { return $query->where('status_kegiatan', 'selesai'); }

    public function crmKatalog()
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            // ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Kegiatan lab telah di-{$eventName}");
    }
}
