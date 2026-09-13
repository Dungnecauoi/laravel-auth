<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Duxbo\LaravelAuth\Actions\AttemptToAuthenticate;
use Duxbo\LaravelAuth\Actions\EnforceSingleSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('admin.auth.login');
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

        return redirect(config('laravel-auth.redirects.login'));
    }
}
