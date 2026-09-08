@extends('laravel-auth::layouts.auth')

@section('content')
    <h1>Two-factor authentication</h1>

    <p style="font-size:.9rem;color:#374151;">Enter the code from your authenticator app, or one of your recovery codes.</p>

    <form method="POST" action="{{ route('two-factor.challenge') }}">
        @csrf

        <label for="code">Authentication code</label>
        <input id="code" type="text" name="code" inputmode="numeric" autocomplete="one-time-code" autofocus>
        @error('code') <div class="error">{{ $message }}</div> @enderror

        <label for="recovery_code">Or a recovery code</label>
        <input id="recovery_code" type="text" name="recovery_code">

        <button type="submit">Verify</button>
    </form>
@endsection
