<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Web\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Duxbo\LaravelAuth\Models\Permission;
use Duxbo\LaravelAuth\Models\Role;

class RoleController
{
    public function index(): View
    {
        return view('laravel-auth::admin.roles.index', [
            'roles' => Role::withCount('users')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('laravel-auth::admin.roles.create', ['permissions' => Permission::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, null);

        $role = Role::create(['name' => $data['name'], 'label' => $data['label'] ?? null]);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', __('laravel-auth::laravel-auth.status.role-created'));
    }

    public function edit(Role $role): View
    {
        $role->load('permissions');

        return view('laravel-auth::admin.roles.edit', ['role' => $role, 'permissions' => Permission::orderBy('name')->get()]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $this->validated($request, $role);

        $role->update(['name' => $data['name'], 'label' => $data['label'] ?? null]);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', __('laravel-auth::laravel-auth.status.role-updated'));
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', __('laravel-auth::laravel-auth.status.role-deleted'));
    }

    protected function validated(Request $request, ?Role $role): array
    {
        $uniqueName = 'unique:roles,name'.($role ? ','.$role->getKey() : '');

        return $request->validate([
            'name' => ['required', 'string', 'max:255', $uniqueName],
            'label' => ['nullable', 'string', 'max:255'],
            'permissions' => ['array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);
    }
}
