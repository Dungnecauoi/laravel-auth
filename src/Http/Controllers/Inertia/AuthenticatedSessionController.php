<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Inertia;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Duxbo\LaravelAuth\Actions\AttemptToAuthenticate;
use Duxbo\LaravelAuth\Actions\EnforceSingleSession;

class AuthenticatedSessionController
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function store(Request $request, AttemptToAuthenticate $authenticator, EnforceSingleSession $singleSession): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $result = $authenticator->attempt(
            $request->only('email', 'password'),
            $request->boolean('remember'),
            AttemptToAuthenticate::throttleKey($request->input('email'), $request->ip())
        );

        if ($result['needs_two_factor']) {
            $request->session()->put('laravel-auth.2fa.user_id', $result['user']->getKey());

            return redirect()->route('two-factor.challenge');
        }

        $request->session()->regenerate();
        $singleSession->forUser($result['user'], $request->session()->getId());

        return redirect()->intended(config('laravel-auth.redirects.home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard(config('laravel-auth.guard'))->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
