<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Duxbo\LaravelAuth\Actions\UpdateUserPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function update(Request $request, UpdateUserPassword $updater): RedirectResponse
    {
        $updater->update($request->user(), $request->all());

        return back()->with('success', __('laravel-auth::laravel-auth.status.password-updated'));
    }
}
