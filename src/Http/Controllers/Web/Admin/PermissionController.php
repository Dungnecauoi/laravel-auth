<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Web\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Duxbo\LaravelAuth\Models\Permission;

class PermissionController
{
    public function index(): View
    {
        return view('laravel-auth::admin.permissions.index', [
            'permissions' => Permission::withCount('roles')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('laravel-auth::admin.permissions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, null);

        Permission::create($data);

        return redirect()->route('admin.permissions.index')->with('status', 'permission-created');
    }

    public function edit(Permission $permission): View
    {
        return view('laravel-auth::admin.permissions.edit', ['permission' => $permission]);
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $permission->update($this->validated($request, $permission));

        return redirect()->route('admin.permissions.index')->with('status', 'permission-updated');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('status', 'permission-deleted');
    }

    protected function validated(Request $request, ?Permission $permission): array
    {
        $uniqueName = 'unique:permissions,name'.($permission ? ','.$permission->getKey() : '');

        return $request->validate([
            'name' => ['required', 'string', 'max:255', $uniqueName],
            'label' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
