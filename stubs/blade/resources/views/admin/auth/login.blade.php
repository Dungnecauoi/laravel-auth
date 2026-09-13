<x-layouts.auth :title="__('Đăng nhập')">
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-admin.input
            label="{{ __('Email') }}" name="email" type="email" value="{{ old('email') }}"
            :error="$errors->first('email')" required autofocus autocomplete="username"
        />

        <x-admin.input
            label="{{ __('Mật khẩu') }}" name="password" type="password"
            :error="$errors->first('password')" required autocomplete="current-password"
        />

        <x-admin.checkbox name="remember" label="{{ __('Ghi nhớ đăng nhập') }}" />

        <x-admin.button type="submit" class="w-full">{{ __('Đăng nhập') }}</x-admin.button>
    </form>

    <x-slot:footer>
        @if (config('laravel-auth.features.password_reset'))
            <p><a href="{{ route('password.request') }}" class="font-medium text-primary-600 hover:text-primary-500">{{ __('Quên mật khẩu?') }}</a></p>
        @endif
        @if (config('laravel-auth.features.registration'))
            <p>{{ __('Chưa có tài khoản?') }} <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">{{ __('Đăng ký') }}</a></p>
        @endif
    </x-slot:footer>
</x-layouts.auth>
