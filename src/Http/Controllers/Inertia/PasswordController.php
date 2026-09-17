<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Inertia;

use Duxbo\LaravelAuth\Actions\UpdateUserPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PasswordController
{
    public function update(Request $request, UpdateUserPassword $updater): RedirectResponse
    {
        $updater->update($request->user(), $request->all());

        return back()->with('success', __('laravel-auth::laravel-auth.status.password-updated'));
    }
}
