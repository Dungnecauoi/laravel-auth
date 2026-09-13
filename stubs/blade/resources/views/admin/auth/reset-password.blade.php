<x-layouts.auth :title="__('Đặt lại mật khẩu')">
    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-admin.input
            label="{{ __('Email') }}" name="email" type="email" value="{{ old('email', $request->query('email')) }}"
            :error="$errors->first('email')" required autofocus autocomplete="username"
        />

        <x-admin.input
            label="{{ __('Mật khẩu mới') }}" name="password" type="password"
            :error="$errors->first('password')" required autocomplete="new-password"
        />

        <x-admin.input
            label="{{ __('Xác nhận mật khẩu mới') }}" name="password_confirmation" type="password"
            required autocomplete="new-password"
        />

        <x-admin.button type="submit" class="w-full">{{ __('Đặt lại mật khẩu') }}</x-admin.button>
    </form>
</x-layouts.auth>
