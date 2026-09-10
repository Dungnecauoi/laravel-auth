<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-neutral-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') · {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-neutral-900 antialiased">
    <nav class="border-b border-neutral-200 bg-white">
        <div class="mx-auto flex max-w-3xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="{{ config('laravel-auth.redirects.home') }}" class="text-base font-semibold text-neutral-900">
                {{ config('app.name') }}
            </a>

            <div class="flex items-center gap-x-4 text-sm">
                <a href="{{ route('profile.edit') }}" class="text-neutral-600 hover:text-neutral-900">{{ __('Hồ sơ') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-neutral-600 hover:text-neutral-900">{{ __('Đăng xuất') }}</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-3xl space-y-6 px-4 py-8 sm:px-6">
        @yield('content')
    </main>

    <x-admin.toast />
</body>
</html>
