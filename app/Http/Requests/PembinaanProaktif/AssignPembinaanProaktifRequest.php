<?php

namespace App\Http\Requests\PembinaanProaktif;

use Illuminate\Foundation\Http\FormRequest;

class AssignPembinaanProaktifRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('assign', $this->route('pembinaan_proaktif'));
    }

    public function rules(): array
    {
        return [
            'assigned_pembina_id' => ['required', 'exists:users,id'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'coordination_channel' => ['nullable', 'string', 'max:60'],
        ];
    }
}