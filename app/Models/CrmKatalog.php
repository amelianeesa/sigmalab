<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmKatalog extends Model
{
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
}