<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Duxbo\LaravelAuth\Actions\CreateNewUser;
use Duxbo\LaravelAuth\Http\Resources\UserResource;

class RegisteredUserController
{
    public function store(Request $request, CreateNewUser $creator): JsonResponse
    {
        $user = $creator->create($request->all());

        $token = $user->createToken($request->userAgent() ?? 'api')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ], 201);
    }
}
