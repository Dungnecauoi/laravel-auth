<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Inertia\Admin;

use Duxbo\LaravelAuth\Actions\Admin\CreateUser;
use Duxbo\LaravelAuth\Actions\Admin\DeleteUser;
use Duxbo\LaravelAuth\Actions\Admin\UpdateUser;
use Duxbo\LaravelAuth\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Authorization lives entirely in routes/inertia-admin.php (`can:`
 * middleware on each route), same as the Blade admin controllers.
 * Validation/persistence live in Actions\Admin\* — this controller only
 * picks a page and hands off to them.
 */
class UserController
{
    public function index(Request $request): Response
    {
        $userModel = config('laravel-auth.user_model');
        $search = $request->string('q')->trim()->toString();

        $users = $userModel::query()
            ->when($search, fn ($query) => $query->where(
                fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")
            ))
            ->with('roles')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users->through(fn ($user) => [
                'id' => $user->getKey(),
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->map(fn ($role) => ['id' => $role->id, 'label' => $role->label ?? $role->name])->all(),
            ]),
            'search' => $search,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create', [
            'roles' => Role::orderBy('name')->get(['id', 'name', 'label']),
        ]);
    }

    public function store(Request $request, CreateUser $creator): RedirectResponse
    {
        $creator->create($request->all());

        return redirect()->route('admin.users.index')->with('success', __('laravel-auth::laravel-auth.status.user-created'));
    }

    public function edit($user): Response
    {
        $user->load('roles');

        return Inertia::render('Admin/Users/Edit', [
            'user' => [
                'id' => $user->getKey(),
                'name' => $user->name,
                'email' => $user->email,
                'role_ids' => $user->roles->pluck('id')->all(),
            ],
            'roles' => Role::orderBy('name')->get(['id', 'name', 'label']),
        ]);
    }

    public function update(Request $request, $user, UpdateUser $updater): RedirectResponse
    {
        $updater->update($user, $request->all());

        return redirect()->route('admin.users.index')->with('success', __('laravel-auth::laravel-auth.status.user-updated'));
    }

    public function destroy(Request $request, $user, DeleteUser $deleter): RedirectResponse
    {
        $deleter->delete($request->user(), $user);

        return redirect()->route('admin.users.index')->with('success', __('laravel-auth::laravel-auth.status.user-deleted'));
    }
}
