<?php

namespace Duxbo\LaravelAuth\Policies;

use Duxbo\LaravelAuth\Models\Role;

/**
 * Standard Laravel policy — only reached when Gate::before (permission-name
 * shortcut) returns null, i.e. the ability isn't itself a granted
 * permission name. Lets you keep using `$user->can('update', $role)` /
 * `@can('update', $role)` the normal Laravel way for the Role model itself.
 */
class RolePolicy
{
    public function viewAny($user): bool
    {
        return $user->hasPermission('laravel-auth.roles.viewAny');
    }

    public function view($user, Role $role): bool
    {
        return $user->hasPermission('laravel-auth.roles.view');
    }

    public function create($user): bool
    {
        return $user->hasPermission('laravel-auth.roles.create');
    }

    public function update($user, Role $role): bool
    {
        return $user->hasPermission('laravel-auth.roles.update');
    }

    public function delete($user, Role $role): bool
    {
        return $user->hasPermission('laravel-auth.roles.delete');
    }
}
