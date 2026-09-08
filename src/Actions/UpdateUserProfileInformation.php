<?php

namespace Duxbo\LaravelAuth\Actions;

use Illuminate\Support\Facades\Validator;

class UpdateUserProfileInformation
{
    public function update($user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.config('laravel-auth.users_table', 'users').',email,'.$user->getKey()],
        ])->validate();

        if ($input['email'] !== $user->email && $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail) {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'email_verified_at' => null,
            ])->save();

            $user->sendEmailVerificationNotification();

            return;
        }

        $user->forceFill(['name' => $input['name'], 'email' => $input['email']])->save();
    }
}
