<?php

namespace Duxbo\LaravelAuth\Actions\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UpdateUser
{
    public function update($user, array $input)
    {
        $usersTable = config('laravel-auth.users_table', 'users');

        $data = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:'.$usersTable.',email,'.$user->getKey()],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'roles' => ['array'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ])->validate();

        $user->fill(['name' => $data['name'], 'email' => $data['email']]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if (method_exists($user, 'roles')) {
            $user->roles()->sync($data['roles'] ?? []);
        }

        return $user;
    }
}
