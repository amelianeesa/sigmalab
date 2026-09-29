<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterToleransi extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel secara eksplisit agar Laravel tidak bingung
    protected $table = 'parameter_toleransi';

    // Mengizinkan semua kolom diisi (kecuali ID)
    protected $guarded = ['id'];

    /**
     * Relasi balik (BelongsTo) ke Parameter Uji
     */
    public function parameterUji()
    {
        // Sesuaikan nama class model ParameterUji milikmu
        return $this->belongsTo(ParameterUji::class, 'parameter_uji_id');
    }
}