<x-layouts.auth :title="__('Tạo tài khoản')">
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <x-admin.input
            label="{{ __('Họ tên') }}" name="name" value="{{ old('name') }}"
            :error="$errors->first('name')" required autofocus autocomplete="name"
        />

        <x-admin.input
            label="{{ __('Email') }}" name="email" type="email" value="{{ old('email') }}"
            :error="$errors->first('email')" required autocomplete="username"
        />

        <x-admin.input
            label="{{ __('Mật khẩu') }}" name="password" type="password"
            :error="$errors->first('password')" required autocomplete="new-password"
        />

        <x-admin.input
            label="{{ __('Xác nhận mật khẩu') }}" name="password_confirmation" type="password"
            required autocomplete="new-password"
        />

        <x-admin.button type="submit" class="w-full">{{ __('Đăng ký') }}</x-admin.button>
    </form>

    <x-slot:footer>
        {{ __('Đã có tài khoản?') }} <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">{{ __('Đăng nhập') }}</a>
    </x-slot:footer>
</x-layouts.auth>
