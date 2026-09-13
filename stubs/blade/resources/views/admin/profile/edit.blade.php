<x-layouts.admin :title="__('Hồ sơ')">
    <x-admin.breadcrumb>
        <x-admin.breadcrumb-item current>{{ __('Hồ sơ') }}</x-admin.breadcrumb-item>
    </x-admin.breadcrumb>

    <div class="space-y-6">
        <x-admin.card :title="__('Thông tin hồ sơ')" :subtitle="__('Cập nhật tên và địa chỉ email của tài khoản.')">
            @include('admin.profile.partials.update-profile-information-form')
        </x-admin.card>

        <x-admin.card :title="__('Đổi mật khẩu')" :subtitle="__('Dùng mật khẩu dài và duy nhất để bảo mật tài khoản.')">
            @include('admin.profile.partials.update-password-form')
        </x-admin.card>

        @if (config('laravel-auth.features.two_factor'))
            <x-admin.card :title="__('Xác thực hai yếu tố')" :subtitle="__('Thêm một lớp bảo mật bằng mã xác thực từ ứng dụng như Google Authenticator.')">
                @include('admin.profile.partials.two-factor-authentication-form')
            </x-admin.card>
        @endif

        @if (config('laravel-auth.features.session_management') && $sessions->isNotEmpty())
            <x-admin.card :title="__('Phiên đăng nhập')" :subtitle="__('Quản lý và đăng xuất các phiên hoạt động trên trình duyệt khác.')">
                @include('admin.profile.partials.session-list')
            </x-admin.card>
        @endif

        <x-admin.card :title="__('Xoá tài khoản')" class="ring-danger-200">
            @include('admin.profile.partials.delete-user-form')
        </x-admin.card>
    </div>
</x-layouts.admin>
