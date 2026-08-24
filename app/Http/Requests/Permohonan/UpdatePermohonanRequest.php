<?php

namespace App\Http\Requests\Permohonan;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermohonanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('permohonan'));
    }

    public function rules(): array
    {
        return [
            'nama_pemohon' => ['sometimes', 'required', 'string', 'max:255'],
            'kontak_pemohon' => ['sometimes', 'required', 'string', 'max:255'],
            'jenis_layanan' => ['sometimes', 'required', 'string', 'max:150'],
            'kebutuhan' => ['sometimes', 'required', 'string'],
        ];
    }
}