@extends('laravel-auth::layouts.auth')

@section('title', __('Quên mật khẩu'))

@section('content')
    <h1 class="text-lg font-semibold text-gray-900">{{ __('Quên mật khẩu') }}</h1>
    <p class="mt-1 text-sm text-gray-500">{{ __('Nhập email của bạn, chúng tôi sẽ gửi liên kết đặt lại mật khẩu.') }}</p>

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Email') }}</label>
            <input
                id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('email') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
            >
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full rounded-md bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            {{ __('Gửi liên kết đặt lại mật khẩu') }}
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">{{ __('Quay lại đăng nhập') }}</a>
    </p>
@endsection
