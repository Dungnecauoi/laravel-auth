<?php

namespace Duxbo\LaravelAuth\Tests;

use Duxbo\LaravelAuth\LaravelAuthServiceProvider;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\SanctumServiceProvider;
use Duxbo\LaravelAuth\Concerns\HasPermissions;
use Duxbo\LaravelAuth\Concerns\HasRoles;
use Duxbo\LaravelAuth\Concerns\TwoFactorAuthenticatable;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
    }

    protected function getPackageProviders($app): array
    {
        return [
            SanctumServiceProvider::class,
            LaravelAuthServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', ['driver' => 'sqlite', 'database' => ':memory:']);
        $app['config']->set('laravel-auth.user_model', TestUser::class);
        $app['config']->set('auth.providers.users.model', TestUser::class);
    }
}

class TestUser extends Authenticatable
{
    use HasApiTokens, HasPermissions, HasRoles, TwoFactorAuthenticatable;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];
}
