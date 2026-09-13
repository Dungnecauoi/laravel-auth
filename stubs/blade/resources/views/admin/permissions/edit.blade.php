<x-layouts.admin :title="__('Sửa quyền')">
    <x-admin.breadcrumb>
        <x-admin.breadcrumb-item :href="route('admin.permissions.index')">{{ __('Quyền') }}</x-admin.breadcrumb-item>
        <x-admin.breadcrumb-item current>{{ $permission->name }}</x-admin.breadcrumb-item>
    </x-admin.breadcrumb>

    <x-admin.card class="max-w-md">
        <form method="POST" action="{{ route('admin.permissions.update', $permission) }}" class="space-y-4">
            @csrf
            @method('PUT')
            @include('admin.permissions._form')
        </form>
    </x-admin.card>
</x-layouts.admin>
