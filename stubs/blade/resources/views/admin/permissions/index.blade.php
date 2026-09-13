<x-layouts.admin :title="__('Quyền')">
    <x-admin.breadcrumb>
        <x-admin.breadcrumb-item current>{{ __('Quyền') }}</x-admin.breadcrumb-item>
    </x-admin.breadcrumb>

    <x-admin.card :padding="false">
        <x-slot:actions>
            <x-admin.button :href="route('admin.permissions.create')" size="sm">{{ __('Thêm quyền') }}</x-admin.button>
        </x-slot:actions>

        <x-admin.table :headers="[__('Tên'), __('Nhãn'), __('Số vai trò'), '']">
            @forelse ($permissions as $permission)
                <tr>
                    <td class="px-4 py-3 font-mono text-xs text-neutral-900">{{ $permission->name }}</td>
                    <td class="px-4 py-3 text-sm text-neutral-600">{{ $permission->label }}</td>
                    <td class="px-4 py-3 text-sm text-neutral-600">{{ $permission->roles_count }}</td>
                    <td class="px-4 py-3 text-right text-sm">
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="font-medium text-primary-600 hover:text-primary-500">{{ __('Sửa') }}</a>
                        <x-admin.confirm-action
                            :id="'delete-permission-'.$permission->id"
                            :action="route('admin.permissions.destroy', $permission)"
                            :message="__('Xoá quyền :name?', ['name' => $permission->name])"
                            class="ml-3"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-sm text-neutral-500">
                        {{ __('Chưa có quyền nào — chạy') }} <code class="font-mono">php artisan laravel-auth:sync-permissions --seed</code>.
                    </td>
                </tr>
            @endforelse
        </x-admin.table>

        <x-admin.pagination :paginator="$permissions" />
    </x-admin.card>
</x-layouts.admin>
