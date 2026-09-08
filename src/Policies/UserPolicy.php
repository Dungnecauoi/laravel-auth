<?php

namespace Duxbo\LaravelAuth\Policies;

/**
 * Registered against config('laravel-auth.user_model') dynamically (see
 * ServiceProvider::registerPolicies), since the User model itself belongs
 * to the host app, not this package.
 */
class UserPolicy
{
    public function viewAny($user): bool
    {
        return $user->hasPermission('laravel-auth.users.viewAny');
    }

    public function view($user, $model): bool
    {
        return $user->hasPermission('laravel-auth.users.view');
    }

    public function create($user): bool
    {
        return $user->hasPermission('laravel-auth.users.create');
    }

    public function update($user, $model): bool
    {
        return $user->hasPermission('laravel-auth.users.update');
    }

    public function delete($user, $model): bool
    {
        return $user->getKey() !== $model->getKey()
            && $user->hasPermission('laravel-auth.users.delete');
    }
}
