@php
    $enabled = $user->twoFactorEnabled();
    $pending = $user->two_factor_secret && ! $enabled;
@endphp

@if (session('recovery_codes'))
    <x-admin.alert type="warning" :title="__('Lưu lại các mã khôi phục này ở nơi an toàn')" class="mb-4">
        <ul class="mt-1 grid grid-cols-2 gap-1 font-mono text-xs">
            @foreach (session('recovery_codes') as $code)
                <li>{{ $code }}</li>
            @endforeach
        </ul>
    </x-admin.alert>
@endif

@if ($enabled)
    <div class="flex items-center gap-x-2">
        <x-admin.badge color="success">{{ __('Đã bật') }}</x-admin.badge>
    </div>

    <div class="mt-4 flex flex-wrap gap-3">
        <form method="POST" action="{{ route('two-factor.recovery-codes') }}">
            @csrf
            <x-admin.button type="submit" variant="secondary" size="sm">{{ __('Tạo lại mã khôi phục') }}</x-admin.button>
        </form>

        <form method="POST" action="{{ route('two-factor.disable') }}">
            @csrf
            @method('DELETE')
            <x-admin.button type="submit" variant="danger" size="sm">{{ __('Tắt xác thực hai yếu tố') }}</x-admin.button>
        </form>
    </div>
@elseif ($pending)
    <div class="flex flex-col items-start gap-4 sm:flex-row">
        <div class="rounded-lg border border-neutral-200 p-3">
            {!! $user->twoFactorQrCodeSvg() !!}
        </div>

        <form method="POST" action="{{ route('two-factor.confirm') }}" class="w-full max-w-xs space-y-3">
            @csrf
            <x-admin.input
                label="{{ __('Nhập mã xác thực để xác nhận') }}" name="code" inputmode="numeric" autocomplete="one-time-code"
                :error="$errors->first('code')" required
            />
            <x-admin.button type="submit">{{ __('Xác nhận') }}</x-admin.button>
        </form>
    </div>
@else
    <form method="POST" action="{{ route('two-factor.enable') }}">
        @csrf
        <x-admin.button type="submit">{{ __('Bật xác thực hai yếu tố') }}</x-admin.button>
    </form>
@endif
