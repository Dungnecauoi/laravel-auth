@php
    $enabled = $user->twoFactorEnabled();
    $pending = $user->two_factor_secret && ! $enabled;
@endphp

<h2 class="text-base font-semibold text-gray-900">{{ __('Xác thực hai yếu tố') }}</h2>
<p class="mt-1 text-sm text-gray-500">
    {{ __('Thêm một lớp bảo mật bằng mã xác thực từ ứng dụng như Google Authenticator.') }}
</p>

@if (session('recovery_codes'))
    <div class="mt-4 rounded-lg bg-amber-50 p-4 text-sm text-amber-800 ring-1 ring-inset ring-amber-200">
        <p class="font-medium">{{ __('Lưu lại các mã khôi phục này ở nơi an toàn:') }}</p>
        <ul class="mt-2 grid grid-cols-2 gap-1 font-mono text-xs">
            @foreach (session('recovery_codes') as $code)
                <li>{{ $code }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($enabled)
    <div class="mt-4 flex items-center gap-x-2">
        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-200">
            {{ __('Đã bật') }}
        </span>
    </div>

    <div class="mt-4 flex flex-wrap gap-x-3 gap-y-2">
        <form method="POST" action="{{ route('two-factor.recovery-codes') }}">
            @csrf
            <button type="submit" class="rounded-md bg-white px-3 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                {{ __('Tạo lại mã khôi phục') }}
            </button>
        </form>

        <form method="POST" action="{{ route('two-factor.disable') }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-md bg-white px-3 py-1.5 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-200 hover:bg-red-50">
                {{ __('Tắt xác thực hai yếu tố') }}
            </button>
        </form>
    </div>
@elseif ($pending)
    <div class="mt-4 flex flex-col items-start gap-4 sm:flex-row">
        <div class="rounded-lg border border-gray-200 p-3">
            {!! $user->twoFactorQrCodeSvg() !!}
        </div>

        <form method="POST" action="{{ route('two-factor.confirm') }}" class="w-full max-w-xs space-y-3">
            @csrf
            <div>
                <label for="code" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Nhập mã xác thực để xác nhận') }}</label>
                <input
                    id="code" type="text" name="code" inputmode="numeric" autocomplete="one-time-code" required
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 {{ $errors->has('code') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
                >
                @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="rounded-md bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                {{ __('Xác nhận') }}
            </button>
        </form>
    </div>
@else
    <form method="POST" action="{{ route('two-factor.enable') }}" class="mt-4">
        @csrf
        <button type="submit" class="rounded-md bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            {{ __('Bật xác thực hai yếu tố') }}
        </button>
    </form>
@endif
