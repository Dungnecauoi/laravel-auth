<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController
{
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(config('laravel-auth.redirects.home'))
            : view('laravel-auth::auth.verify-email');
    }
}
