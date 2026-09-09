<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabelAngkaAcak extends BaseModel
{
    use HasFactory;

    protected $table = 'tabel_angka_acak';
    
    public $timestamps = false;

    protected $fillable = [
        'urutan',
        'nomor_botol',
        'keterangan'
    ];
}
