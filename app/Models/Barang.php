<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'barang_id';

    protected $fillable = [
        'nama_barang',
        'satuan',
        'kode_barang',
        'minimal_stok',
        'saldo_awal',
        'penerimaan',
        'pengeluaran',
        'harga_rata',
        'kondisi',
        'tgl_exp',  
        'saldo_akhir',
        'qr_dicetak_pada',
    ];
}