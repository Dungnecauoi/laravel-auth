<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Duxbo\LaravelAuth\Actions\Admin\CreateRole;
use Duxbo\LaravelAuth\Actions\Admin\UpdateRole;
use Duxbo\LaravelAuth\Models\Permission;
use Duxbo\LaravelAuth\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('admin.roles.index', [
            'roles' => Role::withCount('users')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.roles.create', ['permissions' => Permission::orderBy('name')->get()]);
    }

    public function store(Request $request, CreateRole $creator): RedirectResponse
    {
        $creator->create($request->all());

        return redirect()->route('admin.roles.index')->with('success', __('laravel-auth::laravel-auth.status.role-created'));
    }

    public function edit(Role $role): View
    {
        $role->load('permissions');

        return view('admin.roles.edit', ['role' => $role, 'permissions' => Permission::orderBy('name')->get()]);
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
