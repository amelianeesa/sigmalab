<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class KegiatanRequest extends FormRequest
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
        return [
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|in:pengujian,kalibrasi',
            'kode_sampel' => 'nullable|string|max:50',
            'tanggal_kegiatan' => 'required|date',
            'status_kegiatan' => 'required|in:draft,berjalan,selesai,dibatalkan',
            'alat_ids' => 'nullable|array',
            'alat_ids.*' => 'exists:alat,alat_id',
            'personil_ids' => 'nullable|array',
            'personil_ids.*' => 'exists:personil,personil_id',
            'personil_peran' => 'nullable|array',
            'barang_ids' => 'nullable|array',
            'barang_ids.*' => 'exists:barang,barang_id',
            'barang_jumlah' => 'nullable|array',
            'parameter_uji_ids' => 'nullable|array',
            'parameter_uji_ids.*' => 'exists:parameter_uji,parameter_uji_id',
        ];
    }
}
