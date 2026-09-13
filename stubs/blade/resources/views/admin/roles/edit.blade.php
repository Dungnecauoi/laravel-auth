<x-layouts.admin :title="__('Sửa vai trò')">
    <x-admin.breadcrumb>
        <x-admin.breadcrumb-item :href="route('admin.roles.index')">{{ __('Vai trò') }}</x-admin.breadcrumb-item>
        <x-admin.breadcrumb-item current>{{ $role->label ?? $role->name }}</x-admin.breadcrumb-item>
    </x-admin.breadcrumb>

    <x-admin.card class="max-w-md">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="space-y-4">
            @csrf
            @method('PUT')
            @include('admin.roles._form')
        </form>
    </x-admin.card>
</x-layouts.admin>
