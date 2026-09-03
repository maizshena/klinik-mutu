<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jalur_pendaftaran' => ['required', Rule::in(['kusuka', 'manual'])],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Wajib jika jalur KUSUKA.
            'no_kusuka' => ['required_if:jalur_pendaftaran,kusuka', 'nullable', 'string', 'exists:master_pelaku_usaha,no_kusuka'],
            'catatan_klaim' => ['nullable', 'string', 'max:500'],

            // Wajib jika jalur manual (belum punya KUSUKA).
            'nama_usaha' => ['required_if:jalur_pendaftaran,manual', 'nullable', 'string', 'max:255'],
            'provinsi_id' => ['required_if:jalur_pendaftaran,manual', 'nullable', 'exists:wilayah,id'],
            'kabupaten_id' => ['required_if:jalur_pendaftaran,manual', 'nullable', 'exists:wilayah,id'],
            'alamat' => ['nullable', 'string'],
            'komoditas' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'no_kusuka.exists' => 'Nomor KUSUKA tidak ditemukan di data master. Periksa kembali atau pilih jalur pendaftaran manual.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}