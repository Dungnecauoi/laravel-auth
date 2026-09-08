<h2 class="text-base font-semibold text-red-700">{{ __('Xoá tài khoản') }}</h2>
<p class="mt-1 text-sm text-gray-500">
    {{ __('Một khi tài khoản bị xoá, toàn bộ dữ liệu sẽ bị xoá vĩnh viễn. Vui lòng tải xuống dữ liệu bạn muốn giữ lại trước khi tiếp tục.') }}
</p>

<form
    method="POST" action="{{ route('profile.destroy') }}" class="mt-4 flex items-end gap-x-3"
    x-data="{ confirming: false }" @submit="if (!confirming) { $event.preventDefault(); confirming = true }"
>
    @csrf
    @method('DELETE')

    <div x-show="confirming" x-cloak>
        <label for="delete_password" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Nhập mật khẩu để xác nhận') }}</label>
        <input
            id="delete_password" type="password" name="password" autocomplete="current-password"
            class="block w-full max-w-xs rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('password') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
        >
        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <button type="submit" class="rounded-md bg-red-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
        <span x-show="!confirming">{{ __('Xoá tài khoản') }}</span>
        <span x-show="confirming" x-cloak>{{ __('Xác nhận xoá') }}</span>
    </button>
</form>
