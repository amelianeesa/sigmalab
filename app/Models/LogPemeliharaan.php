<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogPemeliharaan extends BaseModel
{
    use SoftDeletes;
    protected $table = 'log_pemeliharaan';
    protected $fillable = ['alat_id', 'item_id', 'tanggal', 'status', 'tindakan', 'petugas'];

}
