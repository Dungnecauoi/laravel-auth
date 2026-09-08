@php
    $role = $role ?? null;
@endphp

<div>
    <label for="name" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Tên (định danh)') }}</label>
    <input
        id="name" type="text" name="name" value="{{ old('name', $role->name ?? '') }}" required autofocus placeholder="admin"
        class="block w-full max-w-md rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('name') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
    >
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="label" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Nhãn hiển thị') }}</label>
    <input
        id="label" type="text" name="label" value="{{ old('label', $role->label ?? '') }}" placeholder="Quản trị viên"
        class="block w-full max-w-md rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
    >
</div>

<div>
    <span class="mb-1 block text-sm font-medium text-gray-900">{{ __('Quyền') }}</span>
    <div class="grid max-h-64 grid-cols-2 gap-2 overflow-y-auto rounded-md border border-gray-200 p-3">
        @forelse ($permissions as $permission)
            @php
                $checked = collect(old('permissions', $role->permissions->pluck('id')->all() ?? []))->contains($permission->id);
            @endphp
            <label class="flex items-center gap-x-2 text-sm text-gray-700 select-none">
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked($checked) class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                <span class="font-mono text-xs">{{ $permission->name }}</span>
            </label>
        @empty
            <p class="col-span-2 text-sm text-gray-500">
                {{ __('Chưa có quyền nào — chạy') }} <code class="font-mono">php artisan laravel-auth:sync-permissions --seed</code>.
            </p>
        @endforelse
    </div>
</div>

<button type="submit" class="rounded-md bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
    {{ __('Lưu') }}
</button>
