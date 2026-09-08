<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Web;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController
{
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $home = config('laravel-auth.redirects.home').'?verified=1';

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($home);
        }

        $request->fulfill();

        return redirect()->intended($home);
    }
}
