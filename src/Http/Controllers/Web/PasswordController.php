<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Duxbo\LaravelAuth\Actions\UpdateUserPassword;

class PasswordController
{
    public function update(Request $request, UpdateUserPassword $updater): RedirectResponse
    {
        $updater->update($request->user(), $request->all());

        return back()->with('success', __('laravel-auth::laravel-auth.status.password-updated'));
    }
}
