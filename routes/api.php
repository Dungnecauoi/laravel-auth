<?php

use Illuminate\Support\Facades\Route;
use Duxbo\LaravelAuth\Http\Controllers\Api\AuthenticatedSessionController;
use Duxbo\LaravelAuth\Http\Controllers\Api\EmailVerificationController;
use Duxbo\LaravelAuth\Http\Controllers\Api\NewPasswordController;
use Duxbo\LaravelAuth\Http\Controllers\Api\PasswordResetLinkController;
use Duxbo\LaravelAuth\Http\Controllers\Api\RegisteredUserController;
use Duxbo\LaravelAuth\Http\Controllers\Api\TwoFactorChallengeController;

// Token-based auth via Sanctum — for pure API clients (mobile apps, external
// SPAs on a different domain). Inertia/same-domain SPA should use the
// "inertia" flavor instead (Sanctum's stateful cookie guard), not this file.
//
// Wrapped in the "api" middleware group + "/api" prefix ourselves because
// this file is loaded via loadRoutesFrom() directly, bypassing whatever
// api-routing setup (throttle:api, prefix) the host app's bootstrap/app.php
// already declares for its own routes/api.php.

Route::middleware('api')->prefix('api')->group(function () {
    if (config('laravel-auth.features.registration')) {
        Route::post('register', [RegisteredUserController::class, 'store']);
    }

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    if (config('laravel-auth.features.two_factor')) {
        Route::post('two-factor-challenge', [TwoFactorChallengeController::class, 'store'])->middleware('throttle:5,1');
    }

    if (config('laravel-auth.features.password_reset')) {
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store']);
        Route::post('reset-password', [NewPasswordController::class, 'store']);
    }

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);

        if (config('laravel-auth.features.email_verification')) {
            Route::post('email/verification-notification', [EmailVerificationController::class, 'resend'])
                ->middleware('throttle:6,1');

            // Laravel's default VerifyEmail notification always links to
            // route('verification.verify'). If the "web" flavor already
            // registered that name (blade/hybrid), reuse it — the link opens
            // in a browser either way. Only a pure "api" flavor (no web.php
            // loaded at all) needs this file to provide it itself.
            if (! Route::has('verification.verify')) {
                Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
                    ->middleware('signed')
                    ->name('verification.verify');
            }
        }
    });
});
