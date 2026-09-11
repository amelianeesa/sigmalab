<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TindakLanjutRequest extends FormRequest
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
            'status_tindak_lanjut' => 'required|in:belum_ditindaklanjuti,dalam_investigasi,selesai',
            'catatan_investigasi' => 'nullable|string',
        ];

        if ($this->isMethod('post')) {
            $rules['hasil_uji_id'] = 'required|exists:hasil_uji,hasil_uji_id';
        }

        return $rules;
    }
}
