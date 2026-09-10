<x-layouts.admin :title="__('Người dùng')">
    <x-admin.breadcrumb>
        <x-admin.breadcrumb-item current>{{ __('Người dùng') }}</x-admin.breadcrumb-item>
    </x-admin.breadcrumb>

    <x-admin.card :padding="false">
        <x-slot:actions>
            <x-admin.button :href="route('admin.users.create')" size="sm">{{ __('Thêm người dùng') }}</x-admin.button>
        </x-slot:actions>

        <div class="border-b border-neutral-200 p-4">
            <form method="GET" class="max-w-sm">
                <x-admin.input name="q" value="{{ $search }}" placeholder="{{ __('Tìm theo tên hoặc email...') }}" icon="search" />
            </form>
        </div>

        <x-admin.table :headers="[__('Tên'), __('Email'), __('Vai trò'), '']">
            @forelse ($users as $user)
                <tr>
                    <td class="px-4 py-3 text-sm font-medium text-neutral-900">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-sm text-neutral-600">{{ $user->email }}</td>
                    <td class="px-4 py-3 text-sm">
                        @foreach ($user->roles as $role)
                            <x-admin.badge>{{ $role->label ?? $role->name }}</x-admin.badge>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 text-right text-sm">
                        <a href="{{ route('admin.users.edit', $user) }}" class="font-medium text-primary-600 hover:text-primary-500">{{ __('Sửa') }}</a>
                        <x-admin.confirm-action
                            :id="'delete-user-'.$user->id"
                            :action="route('admin.users.destroy', $user)"
                            :message="__('Xoá người dùng :name?', ['name' => $user->name])"
                            class="ml-3"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-sm text-neutral-500">{{ __('Chưa có người dùng nào.') }}</td>
                </tr>
            @endforelse
        </x-admin.table>

        <x-admin.pagination :paginator="$users" />
    </x-admin.card>
</x-layouts.admin>
