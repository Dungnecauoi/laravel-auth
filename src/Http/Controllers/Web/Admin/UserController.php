<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Web\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Duxbo\LaravelAuth\Models\Role;

/**
 * Authorization lives entirely in routes/admin.php (`can:` middleware on
 * each route) — this controller has no idea what a permission is.
 */
class UserController
{
    public function index(Request $request): View
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

        return view('laravel-auth::admin.users.index', ['users' => $users, 'search' => $search]);
    }

    public function create(): View
    {
        return view('laravel-auth::admin.users.create', ['roles' => Role::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, null);

        $userModel = config('laravel-auth.user_model');

        $user = $userModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles($data['roles'] ?? []);
        }

        return redirect()->route('admin.users.index')->with('status', 'user-created');
    }

    public function edit($user): View
    {
        $user->load('roles');

        return view('laravel-auth::admin.users.edit', ['user' => $user, 'roles' => Role::orderBy('name')->get()]);
    }

    public function update(Request $request, $user): RedirectResponse
    {
        $data = $this->validated($request, $user);

        $user->fill(['name' => $data['name'], 'email' => $data['email']]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles($data['roles'] ?? []);
        }

        return redirect()->route('admin.users.index')->with('status', 'user-updated');
    }

    public function destroy($user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'user-deleted');
    }

    protected function validated(Request $request, $user): array
    {
        $usersTable = config('laravel-auth.users_table', 'users');
        $uniqueEmail = 'unique:'.$usersTable.',email'.($user ? ','.$user->getKey() : '');

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $uniqueEmail],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::defaults()],
            'roles' => ['array'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ]);
    }
}
