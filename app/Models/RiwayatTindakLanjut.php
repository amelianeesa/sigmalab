<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatTindakLanjut extends BaseModel
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'riwayat_tindak_lanjut';
    const UPDATED_AT = null;

    protected $fillable = [
        'hasil_uji_id',
        'status_tindak_lanjut',
        'catatan_investigasi',
        'ditindaklanjuti_oleh',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function hasilUji()
    {
        return $this->belongsTo(HasilUji::class, 'hasil_uji_id', 'hasil_uji_id');
    }

    public function penindaklanjut()
    {
        return $this->belongsTo(User::class, 'ditindaklanjuti_oleh', 'users_id');
    }

    public function komentars()
    {
        return $this->hasMany(TindakLanjutKomentar::class, 'riwayat_tindak_lanjut_id', 'riwayat_tindak_lanjut_id');
    }
}
