<?php

use Illuminate\Support\Facades\Route;
use Duxbo\LaravelAuth\Http\Controllers\Inertia\AuthenticatedSessionController;
use Duxbo\LaravelAuth\Http\Controllers\Inertia\EmailVerificationPromptController;
use Duxbo\LaravelAuth\Http\Controllers\Inertia\NewPasswordController;
use Duxbo\LaravelAuth\Http\Controllers\Inertia\PasswordResetLinkController;
use Duxbo\LaravelAuth\Http\Controllers\Inertia\RegisteredUserController;
use Duxbo\LaravelAuth\Http\Controllers\Inertia\TwoFactorChallengeController;
use Duxbo\LaravelAuth\Http\Controllers\Web\EmailVerificationNotificationController;
use Duxbo\LaravelAuth\Http\Controllers\Web\VerifyEmailController;

// Same route names as web.php/api.php (login, register, password.*,
// verification.*) — Sanctum's stateful cookie guard handles the session,
// same as any other same-domain SPA. Only page-rendering controllers are
// Inertia-specific; plain redirect actions (verify/resend) reuse the Web
// controllers since their logic doesn't depend on how the page was built.

Route::middleware('guest')->group(function () {
    if (config('laravel-auth.features.registration')) {
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);
    }

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    if (config('laravel-auth.features.two_factor')) {
        Route::get('two-factor-challenge', [TwoFactorChallengeController::class, 'create'])->name('two-factor.challenge');
        Route::post('two-factor-challenge', [TwoFactorChallengeController::class, 'store']);
    }

    if (config('laravel-auth.features.password_reset')) {
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    }
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    if (config('laravel-auth.features.email_verification')) {
        Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');

        if (! Route::has('verification.verify')) {
            Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware('signed')
                ->name('verification.verify');
        }

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');
    }
});
