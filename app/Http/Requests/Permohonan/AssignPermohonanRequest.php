<?php

namespace App\Http\Requests\Permohonan;

use Illuminate\Foundation\Http\FormRequest;

class AssignPermohonanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('assign', $this->route('permohonan'));
    }

    public function rules(): array
    {
        return [
            'assigned_pembina_id' => ['required', 'exists:users,id'],
            'reason' => ['nullable', 'string', 'max:500'],
        ];
    }
}