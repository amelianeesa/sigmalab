<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBarang extends Model
{
    use HasFactory;

    protected $table = 'transaksi_barang';
    protected $primaryKey = 'transaksi_id';

    protected $fillable = [
        'barang_id',
        'kegiatan_id',
        'jumlah_penerimaan',
        'jumlah_pengeluaran',
        'harga',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'kegiatan_id');
    }
}
