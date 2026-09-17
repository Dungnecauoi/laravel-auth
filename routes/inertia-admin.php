<?php

use Duxbo\LaravelAuth\Http\Controllers\Inertia\Admin\PermissionController;
use Duxbo\LaravelAuth\Http\Controllers\Inertia\Admin\RoleController;
use Duxbo\LaravelAuth\Http\Controllers\Inertia\Admin\UserController;
use Duxbo\LaravelAuth\Models\Permission;
use Duxbo\LaravelAuth\Models\Role;
use Illuminate\Support\Facades\Route;

// Mirrors routes/admin-auth.php (the Blade equivalent, copied into the app
// by laravel-auth:install --stack=blade) exactly, one flavor swapped for
// the other — same 'admin.auth'/'can:' gating, same Actions\Admin\* classes
// underneath. This file stays package-owned like routes/inertia.php, not
// copied out, since it renders Inertia pages rather than Blade views.
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::model('user', config('laravel-auth.user_model', \App\Models\User::class));

    $userModelClass = config('laravel-auth.user_model', \App\Models\User::class);

    Route::get('users', [UserController::class, 'index'])->middleware("can:viewAny,{$userModelClass}")->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->middleware("can:create,{$userModelClass}")->name('users.create');
    Route::post('users', [UserController::class, 'store'])->middleware("can:create,{$userModelClass}")->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->middleware('can:update,user')->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('can:update,user')->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('can:delete,user')->name('users.destroy');

    Route::get('roles', [RoleController::class, 'index'])->middleware('can:viewAny,'.Role::class)->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->middleware('can:create,'.Role::class)->name('roles.create');
    Route::post('roles', [RoleController::class, 'store'])->middleware('can:create,'.Role::class)->name('roles.store');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->middleware('can:update,role')->name('roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->middleware('can:update,role')->name('roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->middleware('can:delete,role')->name('roles.destroy');

    Route::get('permissions', [PermissionController::class, 'index'])->middleware('can:viewAny,'.Permission::class)->name('permissions.index');
    Route::get('permissions/create', [PermissionController::class, 'create'])->middleware('can:create,'.Permission::class)->name('permissions.create');
    Route::post('permissions', [PermissionController::class, 'store'])->middleware('can:create,'.Permission::class)->name('permissions.store');
    Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->middleware('can:update,permission')->name('permissions.edit');
    Route::put('permissions/{permission}', [PermissionController::class, 'update'])->middleware('can:update,permission')->name('permissions.update');
    Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->middleware('can:delete,permission')->name('permissions.destroy');
});
