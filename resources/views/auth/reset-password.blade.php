@extends('laravel-auth::layouts.auth')

@section('content')
    <h1>Reset password</h1>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $request->query('email')) }}" required autofocus>
        @error('email') <div class="error">{{ $message }}</div> @enderror

        <label for="password">New password</label>
        <input id="password" type="password" name="password" required>
        @error('password') <div class="error">{{ $message }}</div> @enderror

        <label for="password_confirmation">Confirm new password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>

        <button type="submit">Reset password</button>
    </form>
@endsection
