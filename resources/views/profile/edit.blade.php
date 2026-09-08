@extends('laravel-auth::layouts.app')

@section('title', __('Hồ sơ'))

@section('content')
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        @include('laravel-auth::profile.partials.update-profile-information-form')
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        @include('laravel-auth::profile.partials.update-password-form')
    </div>

    @if (config('laravel-auth.features.two_factor'))
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            @include('laravel-auth::profile.partials.two-factor-authentication-form')
        </div>
    @endif

    @if (config('laravel-auth.features.session_management') && $sessions->isNotEmpty())
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            @include('laravel-auth::profile.partials.session-list')
        </div>
    @endif

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-red-200">
        @include('laravel-auth::profile.partials.delete-user-form')
    </div>
@endsection
