<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('Đăng nhập')) · {{ config('app.name') }}</title>

    {{-- This package doesn't ship its own Tailwind/Alpine build — it renders
    into whatever Vite entry the host app already compiles. If your app.css
    lives elsewhere, publish this layout and adjust the paths below:
    php artisan vendor:publish --tag=laravel-auth-views --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-full flex-col items-center justify-center px-4 py-10 font-sans text-gray-900 antialiased">
    <div class="w-full max-w-sm">
        <div class="mb-6 flex justify-center">
            <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-900">
                {{ config('app.name') }}
            </a>
        </div>

        @if (session('status'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition
                class="mb-4 flex items-start gap-x-3 rounded-lg bg-green-50 p-4 text-sm text-green-700 ring-1 ring-inset ring-green-200"
            >
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" /><polyline points="8 12.5 11 15.5 16 9" />
                </svg>
                <div class="flex-1">{{ session('status') }}</div>
                <button type="button" @click="show = false" class="shrink-0 text-current opacity-60 hover:opacity-100">
                    <span class="sr-only">{{ __('Đóng') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="6" y1="6" x2="18" y2="18" /><line x1="18" y1="6" x2="6" y2="18" />
                    </svg>
                </button>
            </div>
        @endif

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200 sm:p-8">
            @yield('content')
        </div>
    </div>
</body>
</html>
