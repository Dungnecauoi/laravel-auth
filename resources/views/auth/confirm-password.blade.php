<x-layouts.auth :title="__('Xác nhận mật khẩu')">
    <p class="mb-4 text-sm text-neutral-500">{{ __('Đây là khu vực bảo mật. Vui lòng xác nhận mật khẩu trước khi tiếp tục.') }}</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <x-admin.input
            label="{{ __('Mật khẩu') }}" name="password" type="password"
            :error="$errors->first('password')" required autofocus autocomplete="current-password"
        />

        <x-admin.button type="submit" class="w-full">{{ __('Xác nhận') }}</x-admin.button>
    </form>
</x-layouts.auth>
