@extends('laravel-auth::layouts.auth')

@section('content')
    <h1>Forgot password</h1>

    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email') <div class="error">{{ $message }}</div> @enderror

        <button type="submit">Email password reset link</button>
    </form>

    <div class="links">
        <a href="{{ route('login') }}">Back to login</a>
    </div>
@endsection
