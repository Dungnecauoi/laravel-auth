@php
    $role = $role ?? null;
@endphp

<x-admin.input
    label="{{ __('Tên (định danh)') }}" name="name" value="{{ old('name', $role->name ?? '') }}"
    :error="$errors->first('name')" required autofocus placeholder="admin"
/>

<x-admin.input
    label="{{ __('Nhãn hiển thị') }}" name="label" value="{{ old('label', $role->label ?? '') }}"
    placeholder="Quản trị viên"
/>

<div>
    {{-- Hand-rolled, not <x-admin.checkbox> — that component hardcodes id="{name}",
    which collides across a repeated permissions[] group and breaks label-for. --}}
    <span class="mb-1 block text-sm font-medium text-neutral-900">{{ __('Quyền') }}</span>
    <div class="grid max-h-64 grid-cols-2 gap-2 overflow-y-auto rounded-md border border-neutral-200 p-3">
        @forelse ($permissions as $permission)
            @php
                $checked = collect(old('permissions', $role?->permissions?->pluck('id')->all() ?? []))->contains($permission->id);
            @endphp
            <label for="permission-{{ $permission->id }}" class="flex items-center gap-x-2 text-sm text-neutral-700 select-none">
                <input
                    type="checkbox" name="permissions[]" id="permission-{{ $permission->id }}" value="{{ $permission->id }}" @checked($checked)
                    class="h-4 w-4 rounded border-neutral-300 accent-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-1"
                >
                <span class="font-mono text-xs">{{ $permission->name }}</span>
            </label>
        @empty
            <p class="col-span-2 text-sm text-neutral-500">
                {{ __('Chưa có quyền nào — chạy') }} <code class="font-mono">php artisan laravel-auth:sync-permissions --seed</code>.
            </p>
        @endforelse
    </div>
</div>

<x-admin.button type="submit">{{ __('Lưu') }}</x-admin.button>
