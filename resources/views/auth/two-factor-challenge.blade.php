<x-layouts.auth :title="__('Xác thực hai yếu tố')">
    <div x-data="{ useRecovery: {{ $errors->has('recovery_code') ? 'true' : 'false' }} }">
        <p class="mb-4 text-sm text-neutral-500" x-show="!useRecovery">
            {{ __('Nhập mã từ ứng dụng xác thực của bạn.') }}
        </p>
        <p class="mb-4 text-sm text-neutral-500" x-show="useRecovery" x-cloak>
            {{ __('Nhập một trong các mã khôi phục của bạn.') }}
        </p>

        <form method="POST" action="{{ route('two-factor.challenge') }}" class="space-y-4">
            @csrf

            <div x-show="!useRecovery">
                <x-admin.input
                    label="{{ __('Mã xác thực') }}" name="code" inputmode="numeric" autocomplete="one-time-code"
                    :error="$errors->first('code')" x-bind:required="!useRecovery"
                />
            </div>

            <div x-show="useRecovery" x-cloak>
                <x-admin.input
                    label="{{ __('Mã khôi phục') }}" name="recovery_code"
                    :error="$errors->first('recovery_code')" x-bind:required="useRecovery"
                />
            </div>

            <x-admin.button type="submit" class="w-full">{{ __('Xác nhận') }}</x-admin.button>
        </form>

        <button type="button" @click="useRecovery = !useRecovery" class="mt-4 text-sm font-medium text-primary-600 hover:text-primary-500">
            <span x-show="!useRecovery">{{ __('Dùng mã khôi phục thay thế') }}</span>
            <span x-show="useRecovery" x-cloak>{{ __('Dùng mã xác thực thay thế') }}</span>
        </button>
    </div>
</x-layouts.auth>
