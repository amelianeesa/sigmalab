<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LogPemeliharaan extends Model
{
    protected $table = 'log_pemeliharaan';
    protected $primaryKey = 'log_pemeliharaan_id';
    protected $fillable = ['alat_id', 'item_id', 'tanggal', 'status', 'tindakan', 'petugas'];

}
