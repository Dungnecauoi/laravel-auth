<?php

namespace Duxbo\LaravelAuth\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UpdateUserPassword
{
    public function update($user, array $input): void
    {
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:'.config('laravel-auth.guard', 'web')],
            'password' => ['required', 'confirmed', Password::defaults()],
        ])->validate();

        $user->forceFill(['password' => Hash::make($input['password'])])->save();
    }
}
