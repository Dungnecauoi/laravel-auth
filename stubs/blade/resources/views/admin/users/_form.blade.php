@php
    $user = $user ?? null;
@endphp

<x-admin.input
    label="{{ __('Họ tên') }}" name="name" value="{{ old('name', $user->name ?? '') }}"
    :error="$errors->first('name')" required autofocus
/>

<x-admin.input
    label="{{ __('Email') }}" name="email" type="email" value="{{ old('email', $user->email ?? '') }}"
    :error="$errors->first('email')" required
/>

<x-admin.input
    label="{{ __('Mật khẩu') }}" name="password" type="password"
    :hint="$user ? __('để trống nếu không đổi') : null" :error="$errors->first('password')"
/>

<x-admin.input label="{{ __('Xác nhận mật khẩu') }}" name="password_confirmation" type="password" />

<div>
    {{-- Hand-rolled, not <x-admin.checkbox> — that component hardcodes id="{name}",
    which collides across a repeated roles[] group and breaks label-for. --}}
    <span class="mb-1 block text-sm font-medium text-neutral-900">{{ __('Vai trò') }}</span>
    <div class="flex flex-wrap gap-3">
        @forelse ($roles as $role)
            @php
                $checked = collect(old('roles', $user?->roles?->pluck('id')->all() ?? []))->contains($role->id);
            @endphp
            <label for="role-{{ $role->id }}" class="flex items-center gap-x-2 text-sm text-neutral-700 select-none">
                <input
                    type="checkbox" name="roles[]" id="role-{{ $role->id }}" value="{{ $role->id }}" @checked($checked)
                    class="h-4 w-4 rounded border-neutral-300 accent-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-1"
                >
                {{ $role->label ?? $role->name }}
            </label>
        @empty
            <p class="text-sm text-neutral-500">{{ __('Chưa có vai trò nào — tạo ở mục Vai trò.') }}</p>
        @endforelse
    </div>
</div>

<x-admin.button type="submit">{{ __('Lưu') }}</x-admin.button>
