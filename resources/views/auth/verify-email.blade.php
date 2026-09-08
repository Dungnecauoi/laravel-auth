@extends('laravel-auth::layouts.auth')

@section('title', __('Xác minh email'))

@section('content')
    <div class="flex justify-center">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50">
            <svg class="h-6 w-6 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 6h18v12H3V6Z" /><path d="m3 7 9 6 9-6" />
            </svg>
        </div>
    </div>

    <h1 class="mt-4 text-center text-lg font-semibold text-gray-900">{{ __('Xác minh địa chỉ email') }}</h1>
    <p class="mt-2 text-center text-sm text-gray-500">
        {{ __('Cảm ơn bạn đã đăng ký! Vui lòng nhấp vào liên kết chúng tôi vừa gửi tới email của bạn để tiếp tục.') }}
    </p>

    @if (session('status') === 'verification-link-sent')
        <div class="mt-4 rounded-lg bg-green-50 p-3 text-center text-sm text-green-700 ring-1 ring-inset ring-green-200">
            {{ __('Một liên kết xác minh mới đã được gửi tới email của bạn.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
        @csrf
        <button type="submit" class="w-full rounded-md bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            {{ __('Gửi lại email xác minh') }}
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <button type="submit" class="w-full rounded-md bg-white px-3.5 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            {{ __('Đăng xuất') }}
        </button>
    </form>
@endsection
