<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TindakLanjutKomentar extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'tindak_lanjut_komentar';

    protected $fillable = [
        'riwayat_tindak_lanjut_id',
        'users_id',
        'komentar',
    ];

    public function riwayatTindakLanjut()
    {
        return $this->belongsTo(RiwayatTindakLanjut::class, 'riwayat_tindak_lanjut_id', 'riwayat_tindak_lanjut_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }
}

