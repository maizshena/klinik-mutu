<?php

namespace App\Http\Requests\PembinaanProaktif;

use Illuminate\Foundation\Http\FormRequest;

class SubmitFindingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('submitFindings', $this->route('pembinaan_proaktif'));
    }

    public function rules(): array
    {
        return [
            'findings' => ['required', 'string'],
            'recommendations' => ['required', 'string'],
            'followup_summary' => ['nullable', 'string'],
            'lampiran' => ['nullable', 'array'],
            'lampiran.*' => ['file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ];
    }
}