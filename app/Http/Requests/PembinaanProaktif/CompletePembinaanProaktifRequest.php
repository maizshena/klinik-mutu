<?php

namespace App\Http\Requests\PembinaanProaktif;

use Illuminate\Foundation\Http\FormRequest;

class CompletePembinaanProaktifRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('complete', $this->route('pembinaan_proaktif'));
    }

    public function rules(): array
    {
        return [
            'catatan_penyelesaian' => ['nullable', 'string', 'max:500'],
        ];
    }
}