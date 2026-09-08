<?php

namespace Duxbo\LaravelAuth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Idempotent by design (firstOrCreate on email) — safe to run on every
 * deploy. Does nothing if laravel-auth.admin.email isn't set, so it's
 * harmless to leave wired into a DatabaseSeeder across environments.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('laravel-auth.admin.email');

        if (! $email) {
            return;
        }

        $userModel = config('laravel-auth.user_model');

        $user = $userModel::firstOrCreate(
            ['email' => $email],
            [
                'name' => config('laravel-auth.admin.name', 'Administrator'),
                'password' => Hash::make(config('laravel-auth.admin.password') ?: Str::random(32)),
                'email_verified_at' => now(),
            ]
        );

        if (method_exists($user, 'assignRole')) {
            $user->assignRole(...config('laravel-auth.permissions.super_admin_roles', ['super-admin']));
        }
    }
}
