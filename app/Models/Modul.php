<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modul extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'modul';
    protected $primaryKey = 'modul_id';
    
    protected $fillable = [
        'kode_modul',
        'nama_modul',
    ];
}

