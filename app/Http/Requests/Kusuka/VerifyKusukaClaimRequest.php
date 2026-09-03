<?php

namespace App\Http\Requests\Kusuka;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyKusukaClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('verify', $this->route('claim'));
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in(['setujui', 'tolak'])],
            'review_note' => ['required_if:decision,tolak', 'nullable', 'string', 'max:500'],
        ];
    }
}