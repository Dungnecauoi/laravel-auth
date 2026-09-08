<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Inertia;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Duxbo\LaravelAuth\Actions\CreateNewUser;

class RegisteredUserController
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request, CreateNewUser $creator): RedirectResponse
    {
        $user = $creator->create($request->all());

        Auth::guard(config('laravel-auth.guard'))->login($user);

        return redirect()->intended(config('laravel-auth.redirects.home'));
    }
}
