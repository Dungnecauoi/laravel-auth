<?php

namespace Duxbo\LaravelAuth\Policies;

use Duxbo\LaravelAuth\Models\Permission;

class PermissionPolicy
{
    public function viewAny($user): bool
    {
        return $user->hasPermission('laravel-auth.permissions.viewAny');
    }

    public function view($user, Permission $permission): bool
    {
        return $user->hasPermission('laravel-auth.permissions.view');
    }

    public function create($user): bool
    {
        return $user->hasPermission('laravel-auth.permissions.create');
    }

    public function update($user, Permission $permission): bool
    {
        return $user->hasPermission('laravel-auth.permissions.update');
    }

    public function delete($user, Permission $permission): bool
    {
        return $user->hasPermission('laravel-auth.permissions.delete');
    }
}
