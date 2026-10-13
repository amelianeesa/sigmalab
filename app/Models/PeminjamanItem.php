<?php

namespace App\Models;

class PeminjamanItem extends BaseModel
{
    protected $table = 'peminjaman_item';

    protected $fillable = [
        'peminjaman_id', 'alat_id', 'kondisi_keluar', 'kondisi_kembali', 'catatan_kembali',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'peminjaman_id');
    }

    public function alat()
    {
        // withoutGlobalScopes agar riwayat tetap terbaca walau alat dipindah workspace.
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id')->withoutGlobalScopes()->withTrashed();
    }
}
