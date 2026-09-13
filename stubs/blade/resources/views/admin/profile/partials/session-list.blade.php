<ul class="divide-y divide-neutral-100">
    @foreach ($sessions as $session)
        <li class="flex items-center justify-between gap-x-4 py-3 text-sm">
            <div>
                <p class="font-medium text-neutral-900">
                    {{ $session->ip_address }}
                    @if ($session->is_current_device)
                        <x-admin.badge color="success" class="ml-1">{{ __('thiết bị này') }}</x-admin.badge>
                    @endif
                </p>
                <p class="text-neutral-500">{{ $session->user_agent }} · {{ $session->last_active->diffForHumans() }}</p>
            </div>

            @unless ($session->is_current_device)
                <form method="POST" action="{{ route('sessions.destroy', $session->id) }}">
                    @csrf
                    @method('DELETE')
                    <x-admin.button type="submit" variant="ghost" size="sm">{{ __('Đăng xuất') }}</x-admin.button>
                </form>
            @endunless
        </li>
    @endforeach
</ul>

<form method="POST" action="{{ route('sessions.destroy-others') }}" class="mt-4">
    @csrf
    @method('DELETE')
    <x-admin.button type="submit" variant="secondary" size="sm">{{ __('Đăng xuất tất cả thiết bị khác') }}</x-admin.button>
</form>
