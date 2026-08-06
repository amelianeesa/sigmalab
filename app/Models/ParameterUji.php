<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterUji extends Model
{
    use HasFactory;

    protected $table = 'parameter_uji';
    protected $primaryKey = 'parameter_uji_id';

    protected $fillable = [
        'nama_parameter',
        'satuan',
        'nilai_acuan',
        'batas_bawah',
        'batas_atas',
        'metode_kriteria',
        'rumus_kalkulasi',
        'status_aktif',
    ];

    public function hasilUji()
    {
        return $this->hasMany(HasilUji::class, 'parameter_uji_id', 'parameter_uji_id');
    }

    public function sudahDipakaiDiHasilUji(): bool
    {
        return $this->hasilUji()->exists();
    }
}
