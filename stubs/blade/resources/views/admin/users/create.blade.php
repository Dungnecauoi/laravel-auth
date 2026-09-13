<x-layouts.admin :title="__('Thêm người dùng')">
    <x-admin.breadcrumb>
        <x-admin.breadcrumb-item :href="route('admin.users.index')">{{ __('Người dùng') }}</x-admin.breadcrumb-item>
        <x-admin.breadcrumb-item current>{{ __('Thêm mới') }}</x-admin.breadcrumb-item>
    </x-admin.breadcrumb>

    <x-admin.card class="max-w-md">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf
            @include('admin.users._form')
        </form>
    </x-admin.card>
</x-layouts.admin>
