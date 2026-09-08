<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;
use Duxbo\LaravelAuth\Actions\ResetUserPassword;

class NewPasswordController
{
    public function create(Request $request): View
    {
        return view('laravel-auth::auth.reset-password', ['request' => $request]);
    }

    public function store(Request $request, ResetUserPassword $resetter): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            fn ($user, $password) => $resetter->reset($user, $password)
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('laravel-auth.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
