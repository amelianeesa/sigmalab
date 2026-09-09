<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AlatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'nama_alat' => 'required|string|max:100',
            'merk_tipe' => 'nullable|string|max:100',
            'no_seri' => 'nullable|string|max:100',
            'warna' => 'nullable|string|max:30',
            'ukuran' => 'nullable|string|max:50',
            'kondisi_barang' => 'required|in:baik,rusak',
            'status_barang' => 'required|in:terpakai,idle',
            'unit_kerja_pemilik' => 'nullable|string|max:100',
            'no_sertifikat' => 'nullable|string|max:100',
            'interval_kalibrasi' => 'nullable|string|max:50',
            'tgl_kalibrasi' => 'nullable|date',
            'tgl_akhir' => 'nullable|date',
            'lembaga_kalibrasi' => 'nullable|string|max:150',
            'jenis_kalibrasi' => 'nullable|in:internal,eksternal',
            'range_kapasitas' => 'nullable|string|max:100',
            'faktor_koreksi' => 'nullable|string|max:100',
            'signifikan' => 'nullable|in:ya,tidak',
            'catatan_evaluasi' => 'nullable|string',
        ];

        if ($this->isMethod('post')) {
            $rules['kode_alat'] = 'required|string|max:50|unique:alat,kode_alat';
        }

        return $rules;
    }
}
