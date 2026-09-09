<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatPelatihan extends Model
{
    use SoftDeletes;

    protected $table = 'riwayat_pelatihan';
    protected $primaryKey = 'riwayat_pelatihan_id';

    protected $fillable = [
        'personil_id',
        'nama_pelatihan',
        'penyelenggara',
        'tanggal_mulai',
        'tanggal_selesai',
        'file_sertifikat',
        'status_pelaksanaan',
        'reminder_terkirim',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function personil()
    {
        return $this->belongsTo(Personil::class, 'personil_id', 'personil_id');
    }

    /**
     * PENTING: reset reminder_terkirim ke false setiap kali tanggal_selesai diubah
     * (misal diperpanjang), supaya sistem kirim peringatan lagi untuk siklus berikutnya.
     */
    protected static function booted()
    {
        static::updating(function (RiwayatPelatihan $riwayat) {
            if ($riwayat->isDirty('tanggal_selesai')) {
                $riwayat->reminder_terkirim = false;
            }
        });
    }
}
