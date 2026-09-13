<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Duxbo\LaravelAuth\Actions\CreateNewUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('admin.auth.register');
    }

    public function store(Request $request, CreateNewUser $creator): RedirectResponse
    {
        $user = $creator->create($request->all());

        Auth::guard(config('laravel-auth.guard'))->login($user);

        return redirect()->intended(config('laravel-auth.redirects.home'));
    }
}
