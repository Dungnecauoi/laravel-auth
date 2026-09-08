<?php

namespace Duxbo\LaravelAuth\Actions;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Framework-agnostic: no Request/Response knowledge. Every channel
 * (Web/Api/Inertia) controller calls this and only differs in how it
 * turns the resulting User into a response.
 */
class CreateNewUser
{
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.config('laravel-auth.users_table', 'users')],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ])->validate();

        $userModel = config('laravel-auth.user_model');

        $user = $userModel::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        event(new Registered($user));

        return $user;
    }
}
