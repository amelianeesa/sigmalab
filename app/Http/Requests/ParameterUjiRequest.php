<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ParameterUjiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama_parameter' => 'required|string|max:50',
            'satuan' => 'required|string|max:20',
            'jenis_kontrol' => 'nullable|in:in_house,crm',
            'nilai_acuan' => 'required|numeric',
            'batas_bawah' => 'required|numeric',
            'batas_atas' => 'required|numeric',
            'lcl' => 'nullable|numeric',
            'uwl_bawah' => 'nullable|numeric',
            'mean' => 'nullable|numeric',
            'sd' => 'nullable|numeric',
            'uwl_atas' => 'nullable|numeric',
            'ucl' => 'nullable|numeric',
            'metode_kriteria' => 'nullable|string|max:50',
            'rumus_kalkulasi' => 'nullable|string',
            'toleransi_duplo' => 'nullable|string',
            'langkah_kalkulasi' => 'nullable|array',
            'dependensi_parameter' => 'nullable|array',
            'dependensi_parameter.*' => 'string',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['status_aktif'] = 'boolean';
        }

        return $rules;
    }

    protected function passedValidation()
    {
        $validated = $this->validated();
        
        if (isset($validated['langkah_kalkulasi'])) {
            $cleanedLangkah = [];
            foreach ($validated['langkah_kalkulasi'] as $langkah) {
                if (!empty($langkah['var']) && !empty($langkah['rumus'])) {
                    $cleanedLangkah[] = $langkah;
                }
            }
            // Replace the parameter with cleaned array
            $this->merge(['langkah_kalkulasi' => $cleanedLangkah]);
        }
    }
}
