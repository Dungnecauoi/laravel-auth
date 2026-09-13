<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
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
