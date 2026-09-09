<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class KegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kegiatan' => 'required|string|max:255',
            'crm_katalog_id' => 'required_with:crm_parameter_uji_ids|nullable|exists:crm_katalog,id',
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
            'inhouse_parameter_uji_ids' => 'nullable|array',
            'inhouse_parameter_uji_ids.*' => 'exists:parameter_uji,parameter_uji_id',
            'crm_parameter_uji_ids' => 'nullable|array',
            'crm_parameter_uji_ids.*' => 'exists:parameter_uji,parameter_uji_id',
        ];
    }
    
    public function messages(): array
    {
        return [
            'crm_katalog_id.required_with' => 'Anda harus memilih Botol CRM (Sertifikat Pabrik) jika ada parameter CRM yang dipilih.',
        ];
    }
}
