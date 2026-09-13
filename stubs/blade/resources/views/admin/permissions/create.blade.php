<x-layouts.admin :title="__('Thêm quyền')">
    <x-admin.breadcrumb>
        <x-admin.breadcrumb-item :href="route('admin.permissions.index')">{{ __('Quyền') }}</x-admin.breadcrumb-item>
        <x-admin.breadcrumb-item current>{{ __('Thêm mới') }}</x-admin.breadcrumb-item>
    </x-admin.breadcrumb>

    <x-admin.card class="max-w-md">
        <form method="POST" action="{{ route('admin.permissions.store') }}" class="space-y-4">
            @csrf
            @include('admin.permissions._form')
        </form>
    </x-admin.card>
</x-layouts.admin>
