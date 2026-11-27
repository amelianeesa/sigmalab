<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersyaratanRuangan extends Model
{
    protected $table = 'persyaratan_ruangan';
    protected $primaryKey = 'persyaratan_id';
    protected $guarded = ['persyaratan_id'];

    protected $casts = [
        'suhu_min' => 'float',
        'suhu_max' => 'float',
        'kelembaban_min' => 'float',
        'kelembaban_max' => 'float',
    ];

    public function teksSuhu(): ?string
    {
        return $this->teksRentang($this->suhu_min, $this->suhu_max, '°C');
    }

    public function teksKelembaban(): ?string
    {
        return $this->teksRentang($this->kelembaban_min, $this->kelembaban_max, '%');
    }

    public function suhuDiLuar($nilai): bool
    {
        return $this->diLuar($nilai, $this->suhu_min, $this->suhu_max);
    }

    public function kelembabanDiLuar($nilai): bool
    {
        return $this->diLuar($nilai, $this->kelembaban_min, $this->kelembaban_max);
    }

    private function teksRentang($min, $max, string $satuan): ?string
    {
        return ($min === null || $max === null) ? null : "{$min} - {$max} {$satuan}";
    }

    private function diLuar($nilai, $min, $max): bool
    {
        if ($nilai === null || $nilai === '') {
            return false;
        }

        $nilai = (float) $nilai;

        return ($min !== null && $nilai < $min) || ($max !== null && $nilai > $max);
    }
}