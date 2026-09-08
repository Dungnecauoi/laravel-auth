<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Duxbo\LaravelAuth\Actions\AttemptToAuthenticate;
use Duxbo\LaravelAuth\Actions\EnforceSingleSession;
use Duxbo\LaravelAuth\Http\Resources\UserResource;

class AuthenticatedSessionController
{
    public function store(Request $request, AttemptToAuthenticate $authenticator, EnforceSingleSession $singleSession): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $result = $authenticator->attempt(
            $request->only('email', 'password'),
            false,
            AttemptToAuthenticate::throttleKey($request->input('email'), $request->ip())
        );

        if ($result['needs_two_factor']) {
            return response()->json([
                'two_factor' => true,
                // Short-lived, signed with the app key — never trust a raw
                // client-supplied user id for the 2FA confirm step.
                'login_token' => Crypt::encryptString(json_encode([
                    'id' => $result['user']->getKey(),
                    'expires' => now()->addMinutes(5)->timestamp,
                ])),
            ]);
        }

        $singleSession->forUser($result['user']);
        $token = $result['user']->createToken($request->userAgent() ?? 'api')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($result['user']),
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(status: 204);
    }
}
