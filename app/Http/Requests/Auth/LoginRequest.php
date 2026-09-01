<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['sometimes', 'string'],
            'email' => ['sometimes', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginInput = trim((string) $this->input('login', $this->input('email')));
        $password = $this->input('password');

        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);
        $user = null;

        if ($isEmail) {
            $user = User::where('email', $loginInput)->first();
        } else {
            $cleanedPhone = preg_replace('/[^0-9]/', '', $loginInput);
            $localPhone = str_starts_with($cleanedPhone, '62') ? '0' . substr($cleanedPhone, 2) : $cleanedPhone;
            $intlPhone = str_starts_with($cleanedPhone, '0') ? '62' . substr($cleanedPhone, 1) : $cleanedPhone;

            $user = User::where(function ($q) use ($loginInput, $cleanedPhone, $localPhone, $intlPhone) {
                $q->where('phone', $loginInput)
                  ->orWhere('phone', $cleanedPhone)
                  ->orWhere('phone', $localPhone)
                  ->orWhere('phone', $intlPhone)
                  ->orWhere('phone', '+' . $intlPhone);
            })->first();
        }

        if (! $user || ! Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
                'email' => trans('auth.failed'),
            ]);
        }

        Auth::login($user, $this->boolean('remember'));
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $loginInput = $this->input('login', $this->input('email'));
        return Str::transliterate(Str::lower($loginInput).'|'.$this->ip());
    }
}
