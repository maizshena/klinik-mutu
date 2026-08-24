<?php

namespace App\Http\Requests\Permohonan;

use Illuminate\Foundation\Http\FormRequest;

class StorePermohonanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Permohonan::class);
    }

    public function rules(): array
    {
        return [
            'wilayah_id' => ['nullable', 'exists:wilayah,id'],
            'nama_pemohon' => ['required', 'string', 'max:255'],
            'kontak_pemohon' => ['required', 'string', 'max:255'],
            'jenis_layanan' => ['required', 'string', 'max:150'],
            'kebutuhan' => ['required', 'string'],
        ];
    }
}