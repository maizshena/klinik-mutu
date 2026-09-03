<?php

namespace App\Http\Requests\Followup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyFollowupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('verify', $this->route('pelaku_usaha_followup'));
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in(['selesai', 'perlu_perbaikan'])],
            'review_note' => ['required', 'string', 'max:500'],
        ];
    }
}