<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Duxbo\LaravelAuth\Actions\EnforceSingleSession;
use Duxbo\LaravelAuth\Http\Resources\UserResource;

class TwoFactorChallengeController
{
    public function store(Request $request, EnforceSingleSession $singleSession): JsonResponse
    {
        $request->validate(['login_token' => ['required']]);

        try {
            $payload = json_decode(Crypt::decryptString($request->input('login_token')), true);
        } catch (\Throwable) {
            abort(419, __('laravel-auth::laravel-auth.login_expired'));
        }

        abort_if(($payload['expires'] ?? 0) < now()->timestamp, 419, __('laravel-auth::laravel-auth.login_expired'));

        $userModel = config('laravel-auth.user_model');
        $user = $userModel::findOrFail($payload['id']);

        $valid = $request->filled('recovery_code')
            ? $user->redeemRecoveryCode($request->input('recovery_code'))
            : $user->verifyTwoFactorCode((string) $request->input('code'));

        abort_unless($valid, 422, __('laravel-auth::laravel-auth.two_factor_invalid'));

        $singleSession->forUser($user);
        $token = $user->createToken($request->userAgent() ?? 'api')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }
}
