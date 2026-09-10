@extends('laravel-auth::layouts.app')

@section('title', __('Hồ sơ'))

@section('content')
    <x-admin.card :title="__('Thông tin hồ sơ')" :subtitle="__('Cập nhật tên và địa chỉ email của tài khoản.')">
        @include('laravel-auth::profile.partials.update-profile-information-form')
    </x-admin.card>

    <x-admin.card :title="__('Đổi mật khẩu')" :subtitle="__('Dùng mật khẩu dài và duy nhất để bảo mật tài khoản.')">
        @include('laravel-auth::profile.partials.update-password-form')
    </x-admin.card>

    @if (config('laravel-auth.features.two_factor'))
        <x-admin.card :title="__('Xác thực hai yếu tố')" :subtitle="__('Thêm một lớp bảo mật bằng mã xác thực từ ứng dụng như Google Authenticator.')">
            @include('laravel-auth::profile.partials.two-factor-authentication-form')
        </x-admin.card>
    @endif

    @if (config('laravel-auth.features.session_management') && $sessions->isNotEmpty())
        <x-admin.card :title="__('Phiên đăng nhập')" :subtitle="__('Quản lý và đăng xuất các phiên hoạt động trên trình duyệt khác.')">
            @include('laravel-auth::profile.partials.session-list')
        </x-admin.card>
    @endif

    <x-admin.card :title="__('Xoá tài khoản')" class="ring-danger-200">
        @include('laravel-auth::profile.partials.delete-user-form')
    </x-admin.card>
@endsection
