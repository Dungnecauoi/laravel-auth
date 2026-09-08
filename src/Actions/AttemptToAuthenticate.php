<?php

namespace Duxbo\LaravelAuth\Actions;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AttemptToAuthenticate
{
    /**
     * @return array{user: mixed, needs_two_factor: bool}
     */
    public function attempt(array $credentials, bool $remember, string $throttleKey): array
    {
        $this->ensureIsNotRateLimited($throttleKey);

        $guard = config('laravel-auth.guard', 'web');

        if (! Auth::guard($guard)->attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($throttleKey);

        $user = Auth::guard($guard)->user();

        $needsTwoFactor = config('laravel-auth.features.two_factor')
            && method_exists($user, 'twoFactorEnabled')
            && $user->twoFactorEnabled();

        if ($needsTwoFactor) {
            // Don't leave the user fully authenticated until the 2FA
            // challenge is passed.
            Auth::guard($guard)->logout();
        }

        return ['user' => $user, 'needs_two_factor' => $needsTwoFactor];
    }

    protected function ensureIsNotRateLimited(string $key): void
    {
        if (RateLimiter::tooManyAttempts($key, 5)) {
            event(new Lockout(request()));

            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }
    }

    public static function throttleKey(string $email, string $ip): string
    {
        return Str::transliterate(Str::lower($email).'|'.$ip);
    }
}
