<h2 class="text-base font-semibold text-gray-900">{{ __('Đổi mật khẩu') }}</h2>
<p class="mt-1 text-sm text-gray-500">{{ __('Dùng mật khẩu dài và duy nhất để bảo mật tài khoản.') }}</p>

<form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label for="current_password" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Mật khẩu hiện tại') }}</label>
        <input
            id="current_password" type="password" name="current_password" autocomplete="current-password"
            class="block w-full max-w-md rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('current_password') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
        >
        @error('current_password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Mật khẩu mới') }}</label>
        <input
            id="password" type="password" name="password" autocomplete="new-password"
            class="block w-full max-w-md rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('password') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
        >
        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Xác nhận mật khẩu mới') }}</label>
        <input
            id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
            class="block w-full max-w-md rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
        >
    </div>

    <button type="submit" class="rounded-md bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        {{ __('Lưu') }}
    </button>
</form>
