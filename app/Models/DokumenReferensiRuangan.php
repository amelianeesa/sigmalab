<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenReferensiRuangan extends Model
{
    use HasFactory;

    protected $table = 'dokumen_referensi_ruangans';
    protected $primaryKey = 'dokumen_id';

    protected $fillable = [
        'alat_id',
        'nama_ruangan',
        'bulan',
        'tahun',
        'file_path',
        'nama_file_asli',
    ];
}