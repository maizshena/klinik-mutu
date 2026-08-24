<?php

namespace App\Http\Requests\KonsultasiTeknis;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKonsultasiTeknisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('konsultasi_teknis'));
    }

    public function rules(): array
    {
        return [
            'kategori' => ['sometimes', 'required', 'string', 'max:100'],
            'judul' => ['sometimes', 'required', 'string', 'max:255'],
            'uraian_masalah' => ['sometimes', 'required', 'string'],
            'hasil_diharapkan' => ['nullable', 'string'],
        ];
    }
}