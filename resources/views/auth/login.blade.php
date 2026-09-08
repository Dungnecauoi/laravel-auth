@extends('laravel-auth::layouts.auth')

@section('content')
    <h1>Log in</h1>

    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email') <div class="error">{{ $message }}</div> @enderror

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
        @error('password') <div class="error">{{ $message }}</div> @enderror

        <label><input type="checkbox" name="remember" style="width:auto;display:inline-block;"> Remember me</label>

        <button type="submit">Log in</button>
    </form>

    <div class="links">
        @if (config('laravel-auth.features.password_reset'))
            <a href="{{ route('password.request') }}">Forgot your password?</a><br>
        @endif
        @if (config('laravel-auth.features.registration'))
            <a href="{{ route('register') }}">Create an account</a>
        @endif
    </div>
@endsection
