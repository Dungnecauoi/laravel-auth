<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Duxbo\LaravelAuth\Actions\ResetUserPassword;

class NewPasswordController
{
    public function store(Request $request, ResetUserPassword $resetter): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            fn ($user, $password) => $resetter->reset($user, $password)
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['status' => __($status)])
            : response()->json(['message' => __($status)], 422);
    }
}
