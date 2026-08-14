<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

class Barang extends Model
{
    use SoftDeletes;
    use HasFactory, LogsActivity;

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

    public function transaksiBarang()
    {
        return $this->hasMany(TransaksiBarang::class, 'barang_id', 'barang_id');
    }

    public function permintaanPengadaan()
    {
        return $this->hasMany(PermintaanPengadaan::class, 'barang_id', 'barang_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            // ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Data barang telah di-{$eventName}");
    }
}
