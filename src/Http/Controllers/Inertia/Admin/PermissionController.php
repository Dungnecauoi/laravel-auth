<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Inertia\Admin;

use Duxbo\LaravelAuth\Actions\Admin\CreatePermission;
use Duxbo\LaravelAuth\Actions\Admin\UpdatePermission;
use Duxbo\LaravelAuth\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PermissionController
{
    public function index(): Response
    {
        $permissions = Permission::withCount('roles')->orderBy('name')->paginate(15);

        return Inertia::render('Admin/Permissions/Index', [
            'permissions' => $permissions->through(fn ($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
                'label' => $permission->label,
                'roles_count' => $permission->roles_count,
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Permissions/Create');
    }

    public function store(Request $request, CreatePermission $creator): RedirectResponse
    {
        $creator->create($request->all());

        return redirect()->route('admin.permissions.index')->with('success', __('laravel-auth::laravel-auth.status.permission-created'));
    }

    public function edit(Permission $permission): Response
    {
        return Inertia::render('Admin/Permissions/Edit', [
            'permission' => ['id' => $permission->id, 'name' => $permission->name, 'label' => $permission->label],
        ]);
    }

    public function update(Request $request, Permission $permission, UpdatePermission $updater): RedirectResponse
    {
        $updater->update($permission, $request->all());

        return redirect()->route('admin.permissions.index')->with('success', __('laravel-auth::laravel-auth.status.permission-updated'));
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', __('laravel-auth::laravel-auth.status.permission-deleted'));
    }
}
