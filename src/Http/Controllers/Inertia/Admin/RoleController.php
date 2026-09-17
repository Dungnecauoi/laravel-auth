<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Inertia\Admin;

use Duxbo\LaravelAuth\Actions\Admin\CreateRole;
use Duxbo\LaravelAuth\Actions\Admin\UpdateRole;
use Duxbo\LaravelAuth\Models\Permission;
use Duxbo\LaravelAuth\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoleController
{
    public function index(): Response
    {
        $roles = Role::withCount('users')->orderBy('name')->paginate(15);

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles->through(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'label' => $role->label,
                'users_count' => $role->users_count,
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Roles/Create', [
            'permissions' => Permission::orderBy('name')->get(['id', 'name', 'label']),
        ]);
    }

    public function store(Request $request, CreateRole $creator): RedirectResponse
    {
        $creator->create($request->all());

        return redirect()->route('admin.roles.index')->with('success', __('laravel-auth::laravel-auth.status.role-created'));
    }

    public function edit(Role $role): Response
    {
        $role->load('permissions');

        return Inertia::render('Admin/Roles/Edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'label' => $role->label,
                'permission_ids' => $role->permissions->pluck('id')->all(),
            ],
            'permissions' => Permission::orderBy('name')->get(['id', 'name', 'label']),
        ]);
    }

    public function update(Request $request, Role $role, UpdateRole $updater): RedirectResponse
    {
        $updater->update($role, $request->all());

        return redirect()->route('admin.roles.index')->with('success', __('laravel-auth::laravel-auth.status.role-updated'));
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', __('laravel-auth::laravel-auth.status.role-deleted'));
    }
}
