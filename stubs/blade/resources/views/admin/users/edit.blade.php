<x-layouts.admin :title="__('Sửa người dùng')">
    <x-admin.breadcrumb>
        <x-admin.breadcrumb-item :href="route('admin.users.index')">{{ __('Người dùng') }}</x-admin.breadcrumb-item>
        <x-admin.breadcrumb-item current>{{ $user->name }}</x-admin.breadcrumb-item>
    </x-admin.breadcrumb>

    <x-admin.card class="max-w-md">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf
            @method('PUT')
            @include('admin.users._form')
        </form>
    </x-admin.card>
</x-layouts.admin>
