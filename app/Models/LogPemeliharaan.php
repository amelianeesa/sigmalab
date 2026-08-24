<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogPemeliharaan extends Model
{
    use SoftDeletes;
    protected $table = 'log_pemeliharaan';
    protected $primaryKey = 'log_pemeliharaan_id';
    protected $fillable = ['alat_id', 'item_id', 'tanggal', 'status', 'tindakan', 'petugas'];

}

