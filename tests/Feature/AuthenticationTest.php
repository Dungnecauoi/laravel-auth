<?php

namespace Duxbo\LaravelAuth\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Duxbo\LaravelAuth\Tests\TestCase;
use Duxbo\LaravelAuth\Tests\TestUser;

class AuthenticationTest extends TestCase
{
    public function test_users_can_authenticate_with_correct_credentials(): void
    {
        $user = TestUser::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(config('laravel-auth.redirects.home'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        TestUser::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_super_admin_role_bypasses_every_permission_check(): void
    {
        $user = TestUser::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->assertFalse($user->can('anything.at.all'));

        $user->assignRole(...config('laravel-auth.permissions.super_admin_roles'));

        $this->assertTrue($user->can('anything.at.all'));
    }
}
