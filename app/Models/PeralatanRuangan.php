<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeralatanRuangan extends Model
{
    protected $table = 'peralatan_ruangan';

    protected $primaryKey = 'peralatan_ruangan_id';

    protected $fillable = [
        'nama_ruangan',
        'nama_peralatan',
    ];
}