<?php

namespace Duxbo\LaravelAuth\Actions\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

/**
 * Framework-agnostic: no Request/Response knowledge. Every channel
 * (Web/Api/Inertia) controller calls this and only differs in how it
 * turns the resulting User into a response.
 */
class CreateUser
{
    public function create(array $input)
    {
        $usersTable = config('laravel-auth.users_table', 'users');

        $data = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:'.$usersTable.',email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['array'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ])->validate();

        $userModel = config('laravel-auth.user_model');

        $user = $userModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        if (method_exists($user, 'roles')) {
            // Deliberately not HasRoles::syncRoles() — that helper treats its
            // input as role NAMES (firstOrCreate(['name' => $role])), for the
            // common `$user->assignRole('admin')` usage. This form submits
            // role IDs (checkbox values), so sync the relation directly.
            $user->roles()->sync($data['roles'] ?? []);
        }

        return $user;
    }
}
