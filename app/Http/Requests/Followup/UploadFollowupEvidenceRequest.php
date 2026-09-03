<?php

namespace App\Http\Requests\Followup;

use Illuminate\Foundation\Http\FormRequest;

class UploadFollowupEvidenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('uploadEvidence', $this->route('pelaku_usaha_followup'));
    }

    public function rules(): array
    {
        return [
            'note' => ['nullable', 'string', 'max:500'],
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ];
    }
}