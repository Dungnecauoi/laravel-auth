<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Duxbo\LaravelAuth\Actions\CreateNewUser;

class RegisteredUserController
{
    public function create(): View
    {
        return view('laravel-auth::auth.register');
    }

    public function store(Request $request, CreateNewUser $creator): RedirectResponse
    {
        $user = $creator->create($request->all());

        Auth::guard(config('laravel-auth.guard'))->login($user);

        return redirect()->intended(config('laravel-auth.redirects.home'));
    }
}
