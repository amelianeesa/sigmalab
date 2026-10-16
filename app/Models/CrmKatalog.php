<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class CrmKatalog extends Model
{
    use LogsActivity;
    protected $table = 'crm_katalog';
    protected $fillable = [
        'nama_produk', 'produsen', 'nomor_lot', 'nomor_sertifikat', 'tanggal_expired',
        'is_active', 'coa_file', 'status',
        'verifikasi_administratif_checklist', 'verifikasi_administratif_catatan',
        'verifikasi_administratif_oleh', 'verifikasi_administratif_at',
    ];
    protected $casts = [
        'tanggal_expired' => 'date',
        'is_active' => 'boolean',
        'verifikasi_administratif_checklist' => 'array',
        'verifikasi_administratif_at' => 'datetime',
    ];

    public function sertifikats()
    {
        return $this->hasMany(CrmSertifikat::class, 'crm_katalog_id');
    }

    public function verifikatorAdministratif()
    {
        return $this->belongsTo(Personil::class, 'verifikasi_administratif_oleh', 'personil_id');
    }

    public function verifikasiTeknis()
    {
        return $this->hasMany(CrmVerifikasiTeknis::class, 'crm_katalog_id');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'menunggu_verifikasi_teknis' => 'Menunggu Verifikasi Teknis',
            'aktif' => 'Aktif',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status ?? '-'),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'menunggu_verifikasi', 'menunggu_verifikasi_teknis' => 'bg-warning text-dark',
            'aktif' => 'bg-success',
            'ditolak' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nama_produk',
                'produsen',
                'nomor_lot',
                'nomor_sertifikat',
                'tanggal_expired',
                'status',
                'is_active',
                'verifikasi_administratif_catatan',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {
                $lot = $this->nomor_lot ?? '-';
                if ($eventName === 'created') {
                    return "Menambahkan katalog botol CRM baru: {$this->nama_produk} (Lot: {$lot})";
                }
                if ($eventName === 'updated') {
                    if ($this->wasChanged('status')) {
                        if ($this->status === 'menunggu_verifikasi_teknis') {
                            return "Verifikasi Administratif CRM Lot {$lot}: LOLOS (Lanjut Verifikasi Teknis)";
                        }
                        if ($this->status === 'ditolak') {
                            return "Verifikasi CRM Lot {$lot}: DITOLAK";
                        }
                        if ($this->status === 'aktif') {
                            return "Verifikasi Teknis CRM Lot {$lot}: SELESAI (Botol CRM Resmi AKTIF)";
                        }
                    }
                    if ($this->wasChanged('is_active')) {
                        return $this->is_active 
                            ? "Mengaktifkan botol CRM Lot {$lot}" 
                            : "Menonaktifkan botol CRM Lot {$lot}";
                    }
                    return "Memperbarui informasi katalog CRM Lot {$lot}";
                }
                return "Data katalog CRM Lot {$lot} telah di-{$eventName}";
            });
    }
}