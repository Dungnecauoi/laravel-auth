<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') · {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-gray-900 antialiased">
    <nav class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-3xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="{{ config('laravel-auth.redirects.home') }}" class="text-base font-semibold text-gray-900">
                {{ config('app.name') }}
            </a>

            <div class="flex items-center gap-x-4 text-sm">
                <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-gray-900">{{ __('Hồ sơ') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-gray-900">{{ __('Đăng xuất') }}</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-3xl space-y-6 px-4 py-8 sm:px-6">
        @if (session('status'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition
                class="flex items-start gap-x-3 rounded-lg bg-green-50 p-4 text-sm text-green-700 ring-1 ring-inset ring-green-200"
            >
                <div class="flex-1">{{ __('laravel-auth::laravel-auth.status.'.session('status')) }}</div>
                <button type="button" @click="show = false" class="shrink-0 text-current opacity-60 hover:opacity-100">
                    <span class="sr-only">{{ __('Đóng') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="6" y1="6" x2="18" y2="18" /><line x1="18" y1="6" x2="6" y2="18" />
                    </svg>
                </button>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
