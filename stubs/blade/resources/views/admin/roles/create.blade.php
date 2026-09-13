<x-layouts.admin :title="__('Thêm vai trò')">
    <x-admin.breadcrumb>
        <x-admin.breadcrumb-item :href="route('admin.roles.index')">{{ __('Vai trò') }}</x-admin.breadcrumb-item>
        <x-admin.breadcrumb-item current>{{ __('Thêm mới') }}</x-admin.breadcrumb-item>
    </x-admin.breadcrumb>

    <x-admin.card class="max-w-md">
        <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-4">
            @csrf
            @include('admin.roles._form')
        </form>
    </x-admin.card>
</x-layouts.admin>
