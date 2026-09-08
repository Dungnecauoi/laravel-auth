<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController
{
    public function show(): View
    {
        return view('laravel-auth::auth.confirm-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required']]);

        $confirmed = Auth::guard(config('laravel-auth.guard'))->validate([
            'email' => $request->user()->email,
            'password' => $request->input('password'),
        ]);

        if (! $confirmed) {
            throw ValidationException::withMessages(['password' => __('auth.password')]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(config('laravel-auth.redirects.home'));
    }
}
