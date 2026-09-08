<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Inertia;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Duxbo\LaravelAuth\Actions\EnforceSingleSession;

class TwoFactorChallengeController
{
    public function create(): Response
    {
        return Inertia::render('Auth/TwoFactorChallenge');
    }

    public function store(Request $request, EnforceSingleSession $singleSession): RedirectResponse
    {
        $userId = $request->session()->get('laravel-auth.2fa.user_id');

        abort_unless($userId, 419);

        $userModel = config('laravel-auth.user_model');
        $user = $userModel::findOrFail($userId);

        $valid = $request->filled('recovery_code')
            ? $user->redeemRecoveryCode($request->input('recovery_code'))
            : $user->verifyTwoFactorCode((string) $request->input('code'));

        if (! $valid) {
            throw ValidationException::withMessages([
                'code' => __('laravel-auth::laravel-auth.two_factor_invalid'),
            ]);
        }

        $request->session()->forget('laravel-auth.2fa.user_id');

        Auth::guard(config('laravel-auth.guard'))->login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $singleSession->forUser($user, $request->session()->getId());

        return redirect()->intended(config('laravel-auth.redirects.home'));
    }
}
