<?php

namespace App\Http\Requests\Followup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFollowupProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('updateProgress', $this->route('pelaku_usaha_followup'));
    }

    public function rules(): array
    {
        return [
            'latest_progress' => ['required', 'string'],
            'workflow_status' => ['required', Rule::in([
                'belum_dimulai', 'sedang_dikerjakan', 'menunggu_verifikasi',
            ])],
        ];
    }
}