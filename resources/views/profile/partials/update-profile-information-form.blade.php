<h2 class="text-base font-semibold text-gray-900">{{ __('Thông tin hồ sơ') }}</h2>
<p class="mt-1 text-sm text-gray-500">{{ __('Cập nhật tên và địa chỉ email của tài khoản.') }}</p>

<form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
    @csrf
    @method('PATCH')

    <div>
        <label for="name" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Họ tên') }}</label>
        <input
            id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
            class="block w-full max-w-md rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('name') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
        >
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Email') }}</label>
        <input
            id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
            class="block w-full max-w-md rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('email') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
        >
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

        @if (config('laravel-auth.features.email_verification') && method_exists($user, 'hasVerifiedEmail') && ! $user->hasVerifiedEmail())
            <p class="mt-1 text-sm text-amber-600">{{ __('Email chưa được xác minh.') }}</p>
        @endif
    </div>

    <button type="submit" class="rounded-md bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        {{ __('Lưu') }}
    </button>
</form>
