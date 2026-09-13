<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TwoFactorAuthenticationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->user()->generateTwoFactorSecret();

        return back()->with('success', __('laravel-auth::laravel-auth.status.two-factor-authentication-started'));
    }

    public function confirm(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);

        if (! $request->user()->confirmTwoFactorAuthentication($request->input('code'))) {
            throw ValidationException::withMessages([
                'code' => __('laravel-auth::laravel-auth.two_factor_invalid'),
            ]);
        }

        return back()->with('success', __('laravel-auth::laravel-auth.status.two-factor-authentication-confirmed'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->user()->disableTwoFactorAuthentication();

        return back()->with('success', __('laravel-auth::laravel-auth.status.two-factor-authentication-disabled'));
    }

    public function regenerateRecoveryCodes(Request $request): RedirectResponse
    {
        $request->user()->regenerateRecoveryCodes();

        // Shown once, right here — recovery codes are never re-displayed
        // from storage on a later page load, only right after (re)generation.
        return back()
            ->with('success', __('laravel-auth::laravel-auth.status.recovery-codes-generated'))
            ->with('recovery_codes', $request->user()->recoveryCodes());
    }
}
