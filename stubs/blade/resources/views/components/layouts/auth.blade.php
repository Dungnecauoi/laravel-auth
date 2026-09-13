@props(['title' => null])

<!DOCTYPE html>
<html lang="vi" class="h-full bg-neutral-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? "{$title} · " : '' }}{{ config('admin.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-neutral-900 antialiased" x-data>
    <div class="flex min-h-full flex-col items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex w-full max-w-sm flex-col items-center gap-y-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-600 text-base font-bold text-white">
                {{ mb_substr(config('admin.name'), 0, 1) }}
            </span>
            <span class="text-lg font-semibold text-neutral-900">{{ config('admin.name') }}</span>
        </div>

        <div class="mt-8 w-full max-w-sm rounded-xl bg-white p-6 shadow-sm ring-1 ring-neutral-200 sm:p-8">
            @if($title)
                <h1 class="mb-6 text-center text-xl font-semibold text-neutral-900">{{ $title }}</h1>
            @endif

            {{ $slot }}
        </div>

        @isset($footer)
            <div class="mt-6 text-center text-sm text-neutral-500">
                {{ $footer }}
            </div>
        @endisset
    </div>

    <x-admin.toast />
</body>
</html>
