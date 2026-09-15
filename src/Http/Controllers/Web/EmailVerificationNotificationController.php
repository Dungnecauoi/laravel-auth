<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Plain redirect action — its logic doesn't depend on how the page was
 * built, so both the blade and inertia route files point here instead of
 * each shipping their own copy. Referenced by routes/inertia.php; the
 * blade stack gets its own copy of this under App\Http\Controllers\Auth
 * (see stubs/blade/) since blade views/controllers are meant to be
 * editable in the app, unlike this package-owned one.
 */
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
