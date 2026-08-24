<?php

namespace App\Http\Requests\KonsultasiTeknis;

use Illuminate\Foundation\Http\FormRequest;

class JawabKonsultasiTeknisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('answer', $this->route('konsultasi_teknis'));
    }

    public function rules(): array
    {
        return [
            'jawaban_teknis' => ['required', 'string'],
            'catatan_pusat' => ['nullable', 'string'],
        ];
    }
}