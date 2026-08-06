<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatTindakLanjut extends Model
{
    use HasFactory;

    protected $table = 'riwayat_tindak_lanjut';
    protected $primaryKey = 'riwayat_tindak_lanjut_id';
    public $timestamps = false;

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
}
