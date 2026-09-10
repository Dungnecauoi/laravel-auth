<x-layouts.auth :title="__('Quên mật khẩu')">
    <p class="mb-4 text-sm text-neutral-500">{{ __('Nhập email của bạn, chúng tôi sẽ gửi liên kết đặt lại mật khẩu.') }}</p>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <x-admin.input
            label="{{ __('Email') }}" name="email" type="email" value="{{ old('email') }}"
            :error="$errors->first('email')" required autofocus autocomplete="username"
        />

        <x-admin.button type="submit" class="w-full">{{ __('Gửi liên kết đặt lại mật khẩu') }}</x-admin.button>
    </form>

    <x-slot:footer>
        <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">{{ __('Quay lại đăng nhập') }}</a>
    </x-slot:footer>
</x-layouts.auth>
