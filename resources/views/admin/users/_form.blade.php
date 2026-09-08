@php
    $user = $user ?? null;
@endphp

<div>
    <label for="name" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Họ tên') }}</label>
    <input
        id="name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required autofocus
        class="block w-full max-w-md rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('name') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
    >
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="email" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Email') }}</label>
    <input
        id="email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
        class="block w-full max-w-md rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('email') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
    >
    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="password" class="mb-1 block text-sm font-medium text-gray-900">
        {{ __('Mật khẩu') }} @if ($user) <span class="font-normal text-gray-400">({{ __('để trống nếu không đổi') }})</span> @endif
    </label>
    <input
        id="password" type="password" name="password" autocomplete="new-password"
        class="block w-full max-w-md rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('password') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
    >
    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Xác nhận mật khẩu') }}</label>
    <input
        id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
        class="block w-full max-w-md rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
    >
</div>

<div>
    <span class="mb-1 block text-sm font-medium text-gray-900">{{ __('Vai trò') }}</span>
    <div class="flex flex-wrap gap-3">
        @forelse ($roles as $role)
            @php
                $checked = collect(old('roles', $user->roles->pluck('id')->all() ?? []))->contains($role->id);
            @endphp
            <label class="flex items-center gap-x-2 text-sm text-gray-700 select-none">
                <input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked($checked) class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                {{ $role->label ?? $role->name }}
            </label>
        @empty
            <p class="text-sm text-gray-500">{{ __('Chưa có vai trò nào — tạo ở mục Vai trò.') }}</p>
        @endforelse
    </div>
</div>

<button type="submit" class="rounded-md bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
    {{ __('Lưu') }}
</button>
