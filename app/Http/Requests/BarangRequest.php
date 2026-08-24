<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BarangRequest extends FormRequest
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
            'nama_barang' => 'required|string|max:100',
            'satuan' => 'required|string|max:20',
            'minimal_stok' => 'nullable|numeric',
            'saldo_awal' => 'nullable|numeric',
            'penerimaan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric',
            'harga_rata' => 'nullable|numeric',
            'kondisi' => 'required|in:baik,rusak',
            'tgl_exp' => 'nullable|date',
        ];

        if ($this->isMethod('post')) {
            $rules['kode_barang'] = 'required|string|max:50|unique:barang,kode_barang';
        }

        return $rules;
    }
}
