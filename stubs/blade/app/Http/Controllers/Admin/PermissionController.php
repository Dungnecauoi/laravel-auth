<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Duxbo\LaravelAuth\Actions\Admin\CreatePermission;
use Duxbo\LaravelAuth\Actions\Admin\UpdatePermission;
use Duxbo\LaravelAuth\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(): View
    {
        return view('admin.permissions.index', [
            'permissions' => Permission::withCount('roles')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request, CreatePermission $creator): RedirectResponse
    {
        $creator->create($request->all());

        return redirect()->route('admin.permissions.index')->with('success', __('laravel-auth::laravel-auth.status.permission-created'));
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', ['permission' => $permission]);
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
