<?php

namespace Duxbo\LaravelAuth\Concerns;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/**
 * Add to your app's User model: `use TwoFactorAuthenticatable;`
 *
 * Requires "pragmarx/google2fa" (composer require pragmarx/google2fa) when
 * config('laravel-auth.features.two_factor') is enabled. Every method here
 * degrades to a clear exception if the package isn't installed, rather than
 * failing silently.
 */
trait TwoFactorAuthenticatable
{
    public function twoFactorEnabled(): bool
    {
        return ! is_null($this->two_factor_confirmed_at);
    }

    public function generateTwoFactorSecret(): string
    {
        $secret = $this->google2fa()->generateSecretKey();

        $this->forceFill([
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_recovery_codes' => Crypt::encryptString(json_encode($this->generateRecoveryCodes())),
            'two_factor_confirmed_at' => null,
        ])->save();

        return $secret;
    }

    public function confirmTwoFactorAuthentication(string $code): bool
    {
        if (! $this->verifyTwoFactorCode($code)) {
            return false;
        }

        $this->forceFill(['two_factor_confirmed_at' => now()])->save();

        return true;
    }

    public function disableTwoFactorAuthentication(): void
    {
        $this->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    public function verifyTwoFactorCode(string $code): bool
    {
        if (! $this->two_factor_secret) {
            return false;
        }

        $secret = Crypt::decryptString($this->two_factor_secret);

        return (bool) $this->google2fa()->verifyKey($secret, $code);
    }

    public function recoveryCodes(): array
    {
        return $this->two_factor_recovery_codes
            ? json_decode(Crypt::decryptString($this->two_factor_recovery_codes), true)
            : [];
    }

    public function redeemRecoveryCode(string $code): bool
    {
        $codes = $this->recoveryCodes();

        if (! in_array($code, $codes, true)) {
            return false;
        }

        $this->forceFill([
            'two_factor_recovery_codes' => Crypt::encryptString(json_encode(
                array_values(array_diff($codes, [$code]))
            )),
        ])->save();

        return true;
    }

    public function twoFactorQrCodeUrl(): string
    {
        $secret = Crypt::decryptString($this->two_factor_secret);

        return $this->google2fa()->getQRCodeUrl(
            config('app.name'),
            $this->email,
            $secret,
        );
    }

    protected function generateRecoveryCodes(): array
    {
        return collect(range(1, 8))
            ->map(fn () => Str::random(10).'-'.Str::random(10))
            ->all();
    }

    protected function google2fa()
    {
        if (! class_exists(\PragmaRX\Google2FA\Google2FA::class)) {
            throw new \RuntimeException(
                'Two-factor authentication requires "pragmarx/google2fa". Run: composer require pragmarx/google2fa'
            );
        }

        return new \PragmaRX\Google2FA\Google2FA();
    }
}
