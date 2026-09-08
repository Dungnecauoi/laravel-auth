<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') · {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-gray-900 antialiased" x-data="{ sidebarOpen: false }">
    <div class="lg:flex lg:h-full">
        <aside
            class="fixed inset-y-0 left-0 z-20 w-64 -translate-x-full border-r border-gray-200 bg-white transition-transform lg:static lg:translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen }"
        >
            <div class="flex h-14 items-center border-b border-gray-200 px-4">
                <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-gray-900">{{ config('app.name') }} · Admin</a>
            </div>
            <nav class="space-y-1 p-3 text-sm">
                <a href="{{ route('admin.users.index') }}" class="block rounded-md px-3 py-2 font-medium {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                    {{ __('Người dùng') }}
                </a>
                <a href="{{ route('admin.roles.index') }}" class="block rounded-md px-3 py-2 font-medium {{ request()->routeIs('admin.roles.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                    {{ __('Vai trò') }}
                </a>
                <a href="{{ route('admin.permissions.index') }}" class="block rounded-md px-3 py-2 font-medium {{ request()->routeIs('admin.permissions.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                    {{ __('Quyền') }}
                </a>
                <a href="{{ config('laravel-auth.redirects.home') }}" class="mt-4 block rounded-md px-3 py-2 font-medium text-gray-500 hover:bg-gray-50">
                    &larr; {{ __('Về trang chính') }}
                </a>
            </nav>
        </aside>

        <div class="flex-1 lg:pl-0">
            <header class="flex h-14 items-center justify-between border-b border-gray-200 bg-white px-4 lg:hidden">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="4" y1="6" x2="20" y2="6" /><line x1="4" y1="12" x2="20" y2="12" /><line x1="4" y1="18" x2="20" y2="18" />
                    </svg>
                </button>
                <span class="text-sm font-semibold">@yield('title')</span>
                <span></span>
            </header>

            <main class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-lg font-semibold text-gray-900">@yield('title')</h1>
                    @yield('actions')
                </div>

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
        </div>
    </div>
</body>
</html>
