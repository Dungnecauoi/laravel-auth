@extends('laravel-auth::layouts.auth')

@section('content')
    <h1>Verify your email</h1>

    <p style="font-size:.9rem;color:#374151;">
        Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed you.
    </p>

    @if (session('status') === 'verification-link-sent')
        <div class="status">A new verification link has been sent to your email address.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" style="margin-top:1rem;">
        @csrf
        <button type="submit">Resend verification email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top:.5rem;">
        @csrf
        <button type="submit" style="background:#e5e7eb;color:#111827;">Log out</button>
    </form>
@endsection
