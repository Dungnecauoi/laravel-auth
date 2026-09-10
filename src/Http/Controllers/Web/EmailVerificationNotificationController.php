<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController
{
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(config('laravel-auth.redirects.home'));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', __('laravel-auth::laravel-auth.verification_sent'));
    }
}
