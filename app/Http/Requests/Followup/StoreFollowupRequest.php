<?php

namespace App\Http\Requests\Followup;

use Illuminate\Foundation\Http\FormRequest;

class StoreFollowupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\PelakuUsahaFollowup::class);
    }

    public function rules(): array
    {
        return [
            'profile_id' => ['required', 'exists:pelaku_usaha_profiles,id'],
            'master_pelaku_id' => ['nullable', 'exists:master_pelaku_usaha,id'],
            'pembinaan_id' => ['nullable', 'exists:pembinaan_proaktif,id'],
            'permohonan_id' => ['nullable', 'exists:permohonan,id'],
            'konsultasi_teknis_id' => ['nullable', 'exists:konsultasi_teknis,id'],
            'title' => ['required', 'string', 'max:255'],
            'finding' => ['required', 'string'],
            'action_plan' => ['required', 'string'],
            'responsible_party' => ['required', 'in:pelaku_usaha,pembina,bersama'],
            'assigned_pembina_id' => ['required', 'exists:users,id'],
            'priority' => ['required', 'in:rendah,normal,tinggi'],
            'start_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }
}