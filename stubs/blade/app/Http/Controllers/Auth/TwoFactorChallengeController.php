<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Duxbo\LaravelAuth\Actions\EnforceSingleSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorChallengeController extends Controller
{
    public function create(): View
    {
        return view('admin.auth.two-factor-challenge');
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
