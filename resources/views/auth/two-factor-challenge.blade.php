@extends('laravel-auth::layouts.auth')

@section('title', __('Xác thực hai yếu tố'))

@section('content')
    <h1 class="text-lg font-semibold text-gray-900">{{ __('Xác thực hai yếu tố') }}</h1>

    <div x-data="{ useRecovery: {{ $errors->has('recovery_code') ? 'true' : 'false' }} }">
        <p class="mt-1 text-sm text-gray-500" x-show="!useRecovery">
            {{ __('Nhập mã từ ứng dụng xác thực của bạn.') }}
        </p>
        <p class="mt-1 text-sm text-gray-500" x-show="useRecovery" x-cloak>
            {{ __('Nhập một trong các mã khôi phục của bạn.') }}
        </p>

        <form method="POST" action="{{ route('two-factor.challenge') }}" class="mt-6 space-y-4">
            @csrf

            <div x-show="!useRecovery">
                <label for="code" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Mã xác thực') }}</label>
                <input
                    id="code" type="text" name="code" inputmode="numeric" autocomplete="one-time-code"
                    x-bind:required="!useRecovery" x-bind:autofocus="!useRecovery"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('code') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
                >
                @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div x-show="useRecovery" x-cloak>
                <label for="recovery_code" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Mã khôi phục') }}</label>
                <input
                    id="recovery_code" type="text" name="recovery_code" x-bind:required="useRecovery"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('recovery_code') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
                >
                @error('recovery_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full rounded-md bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                {{ __('Xác nhận') }}
            </button>
        </form>

        <button type="button" @click="useRecovery = !useRecovery" class="mt-4 text-sm font-medium text-indigo-600 hover:text-indigo-500">
            <span x-show="!useRecovery">{{ __('Dùng mã khôi phục thay thế') }}</span>
            <span x-show="useRecovery" x-cloak>{{ __('Dùng mã xác thực thay thế') }}</span>
        </button>
    </div>
@endsection
