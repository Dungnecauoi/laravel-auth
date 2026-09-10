<x-layouts.admin :title="__('Vai trò')">
    <x-admin.breadcrumb>
        <x-admin.breadcrumb-item current>{{ __('Vai trò') }}</x-admin.breadcrumb-item>
    </x-admin.breadcrumb>

    <x-admin.card :padding="false">
        <x-slot:actions>
            <x-admin.button :href="route('admin.roles.create')" size="sm">{{ __('Thêm vai trò') }}</x-admin.button>
        </x-slot:actions>

        <x-admin.table :headers="[__('Tên'), __('Số người dùng'), '']">
            @forelse ($roles as $role)
                <tr>
                    <td class="px-4 py-3 text-sm font-medium text-neutral-900">
                        {{ $role->label ?? $role->name }} <span class="font-normal text-neutral-400">({{ $role->name }})</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-neutral-600">{{ $role->users_count }}</td>
                    <td class="px-4 py-3 text-right text-sm">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="font-medium text-primary-600 hover:text-primary-500">{{ __('Sửa') }}</a>
                        <x-admin.confirm-action
                            :id="'delete-role-'.$role->id"
                            :action="route('admin.roles.destroy', $role)"
                            :message="__('Xoá vai trò :name?', ['name' => $role->label ?? $role->name])"
                            class="ml-3"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-6 text-center text-sm text-neutral-500">{{ __('Chưa có vai trò nào.') }}</td>
                </tr>
            @endforelse
        </x-admin.table>

        <x-admin.pagination :paginator="$roles" />
    </x-admin.card>
</x-layouts.admin>
