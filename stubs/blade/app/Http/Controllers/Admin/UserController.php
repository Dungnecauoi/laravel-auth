<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Duxbo\LaravelAuth\Actions\Admin\CreateUser;
use Duxbo\LaravelAuth\Actions\Admin\DeleteUser;
use Duxbo\LaravelAuth\Actions\Admin\UpdateUser;
use Duxbo\LaravelAuth\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Authorization lives entirely in routes/admin.php (`can:` middleware on
 * each route). Validation and persistence live in duxbo/laravel-auth's
 * Actions\Admin\* classes — this controller only picks a view and hands
 * off to them, the same as the auth controllers in ../Auth/ do.
 */
class UserController extends Controller
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

        return view('admin.users.index', ['users' => $users, 'search' => $search]);
    }

    public function create(): View
    {
        return view('admin.users.create', ['roles' => Role::orderBy('name')->get()]);
    }

    public function store(Request $request, CreateUser $creator): RedirectResponse
    {
        $creator->create($request->all());

        return redirect()->route('admin.users.index')->with('success', __('laravel-auth::laravel-auth.status.user-created'));
    }

    public function edit($user): View
    {
        $user->load('roles');

        return view('admin.users.edit', ['user' => $user, 'roles' => Role::orderBy('name')->get()]);
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
