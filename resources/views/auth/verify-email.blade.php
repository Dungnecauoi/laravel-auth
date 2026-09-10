<x-layouts.auth :title="__('Xác minh email')">
    <div class="flex justify-center">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary-50">
            <x-admin.icon name="check-circle" class="h-6 w-6 text-primary-600" />
        </div>
    </div>

    <h1 class="mt-4 text-center text-lg font-semibold text-neutral-900">{{ __('Xác minh địa chỉ email') }}</h1>
    <p class="mt-2 text-center text-sm text-neutral-500">
        {{ __('Cảm ơn bạn đã đăng ký! Vui lòng nhấp vào liên kết chúng tôi vừa gửi tới email của bạn để tiếp tục.') }}
    </p>

    <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
        @csrf
        <x-admin.button type="submit" class="w-full">{{ __('Gửi lại email xác minh') }}</x-admin.button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <x-admin.button type="submit" variant="secondary" class="w-full">{{ __('Đăng xuất') }}</x-admin.button>
    </form>
</x-layouts.auth>
