<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Otentikasi manual karena legacy pakai kolom `password_hash`, bukan `password`.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = \App\Models\User::where('email', $this->input('email'))->first();

        if (! $user || ! Hash::check($this->input('password'), $user->password_hash)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi yang dimasukkan salah.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Auth::login($user, $this->boolean('remember'));
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}