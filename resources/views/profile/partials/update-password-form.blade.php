<form method="POST" action="{{ route('password.update') }}" class="max-w-md space-y-4">
    @csrf
    @method('PUT')

    <x-admin.input
        label="{{ __('Mật khẩu hiện tại') }}" name="current_password" type="password"
        :error="$errors->first('current_password')" autocomplete="current-password"
    />

    <x-admin.input
        label="{{ __('Mật khẩu mới') }}" name="password" type="password"
        :error="$errors->first('password')" autocomplete="new-password"
    />

    <x-admin.input
        label="{{ __('Xác nhận mật khẩu mới') }}" name="password_confirmation" type="password"
        autocomplete="new-password"
    />

    <x-admin.button type="submit">{{ __('Lưu') }}</x-admin.button>
</form>
