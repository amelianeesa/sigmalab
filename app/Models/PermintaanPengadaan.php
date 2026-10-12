<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PermintaanPengadaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'permintaan_pengadaan';
    protected $primaryKey = 'permintaan_id';

    protected $fillable = [
        'barang_id',
        'jumlah_diminta',
        'target_hari',
        'alasan',
        'foto',
        'status',
        'diajukan_oleh',
        'disetujui_oleh',
        'tanggal_pengajuan',
        'tanggal_keputusan',
        'catatan_approval',
        'nama_penerima',
        'foto_diterima',
        'waktu_diterima',
        'catatan_po'
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_keputusan' => 'datetime',
        'waktu_diterima' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    public function pemohon()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh', 'users_id');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'users_id');
    }

    public function logs()
    {
        return $this->hasMany(PengadaanLog::class, 'permintaan_id', 'permintaan_id');
    }

    public function catatTahap(int $tahap, string $aksi, $userId = null, $waktu = null)
    {
        return $this->logs()->updateOrCreate(
            ['tahap' => $tahap],
            [
                'aksi' => $aksi,
                'users_id' => $userId ?? Auth::id(),
                'dicatat_pada' => $waktu ?? Carbon::now('Asia/Jakarta'),
            ]
        );
    }

    public function getFormatTargetWaktuAttribute()
    {
        $totalHari = $this->target_hari;
        if (!$totalHari || $totalHari <= 0) {
            return '-';
        }

        $tahun = floor($totalHari / 365);
        $sisa = $totalHari % 365;
        $bulan = floor($sisa / 30);
        $hari = $sisa % 30;

        $str = [];
        if ($tahun > 0) $str[] = "{$tahun} thn";
        if ($bulan > 0) $str[] = "{$bulan} bln";
        if ($hari > 0 || empty($str)) $str[] = "{$hari} hari";

        return implode(' ', $str);
    }
}