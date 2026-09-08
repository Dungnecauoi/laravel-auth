<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Inertia;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController
{
    public function __invoke(Request $request): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(config('laravel-auth.redirects.home'))
            : Inertia::render('Auth/VerifyEmail', ['status' => $request->session()->get('status')]);
    }
}
