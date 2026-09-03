<?php

namespace App\Http\Requests\PembinaanProaktif;

use Illuminate\Foundation\Http\FormRequest;

class StorePembinaanProaktifRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\PembinaanProaktif::class);
    }

    public function rules(): array
    {
        return [
            'profile_id' => ['nullable', 'exists:pelaku_usaha_profiles,id'],
            'target_province_id' => ['required', 'exists:wilayah,id'],
            'target_district_id' => ['nullable', 'exists:wilayah,id'],
            'directive_type' => ['required', 'string', 'max:60'],
            'directive_date' => ['required', 'date'],
            'directive_number' => ['nullable', 'string', 'max:150'],
            'directive_note' => ['nullable', 'string'],
            'tujuan' => ['required', 'string'],
            'ruang_lingkup' => ['required', 'string'],
            'prioritas' => ['required', 'in:rendah,normal,tinggi,darurat'],
            'target_date' => ['nullable', 'date', 'after_or_equal:directive_date'],
        ];
    }
}