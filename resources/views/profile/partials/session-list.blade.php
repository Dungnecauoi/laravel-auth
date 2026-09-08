<h2 class="text-base font-semibold text-gray-900">{{ __('Phiên đăng nhập') }}</h2>
<p class="mt-1 text-sm text-gray-500">{{ __('Quản lý và đăng xuất các phiên hoạt động trên trình duyệt khác.') }}</p>

<ul class="mt-4 divide-y divide-gray-100">
    @foreach ($sessions as $session)
        <li class="flex items-center justify-between gap-x-4 py-3 text-sm">
            <div>
                <p class="font-medium text-gray-900">
                    {{ $session->ip_address }}
                    @if ($session->is_current_device)
                        <span class="ml-1 text-xs font-normal text-green-600">{{ __('(thiết bị này)') }}</span>
                    @endif
                </p>
                <p class="text-gray-500">{{ $session->user_agent }} · {{ $session->last_active->diffForHumans() }}</p>
            </div>

            @unless ($session->is_current_device)
                <form method="POST" action="{{ route('sessions.destroy', $session->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500">{{ __('Đăng xuất') }}</button>
                </form>
            @endunless
        </li>
    @endforeach
</ul>

<form method="POST" action="{{ route('sessions.destroy-others') }}" class="mt-4">
    @csrf
    @method('DELETE')
    <button type="submit" class="rounded-md bg-white px-3 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
        {{ __('Đăng xuất tất cả thiết bị khác') }}
    </button>
</form>
