<?php

namespace App\Http\Requests\KonsultasiTeknis;

use Illuminate\Foundation\Http\FormRequest;

class StoreKonsultasiTeknisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\KonsultasiTeknis::class);
    }

    public function rules(): array
    {
        return [
            'profile_id' => ['required', 'exists:pelaku_usaha_profiles,id'],
            'master_pelaku_id' => ['nullable', 'exists:master_pelaku_usaha,id'],
            'origin_wilayah_id' => ['required', 'exists:wilayah,id'],
            'kategori' => ['required', 'string', 'max:100'],
            'judul' => ['required', 'string', 'max:255'],
            'komoditas' => ['nullable', 'string', 'max:150'],
            'jenis_usaha' => ['nullable', 'string', 'max:150'],
            'uraian_masalah' => ['required', 'string'],
            'hasil_diharapkan' => ['nullable', 'string'],
            'lampiran' => ['nullable', 'array'],
            'lampiran.*' => ['file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ];
    }
}