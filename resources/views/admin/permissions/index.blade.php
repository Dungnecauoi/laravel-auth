@extends('laravel-auth::layouts.admin')

@section('title', __('Quyền'))

@section('actions')
    <a href="{{ route('admin.permissions.create') }}" class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
        {{ __('Thêm quyền') }}
    </a>
@endsection

@section('content')
    <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead>
                <tr class="text-left text-gray-500">
                    <th class="px-4 py-3 font-medium">{{ __('Tên') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('Nhãn') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('Số vai trò') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($permissions as $permission)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs text-gray-900">{{ $permission->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $permission->label }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $permission->roles_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.permissions.edit', $permission) }}" class="font-medium text-indigo-600 hover:text-indigo-500">{{ __('Sửa') }}</a>
                            <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" class="ml-3 inline" onsubmit="return confirm('{{ __('Xoá quyền này?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 hover:text-red-500">{{ __('Xoá') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                            {{ __('Chưa có quyền nào — chạy') }} <code class="font-mono">php artisan laravel-auth:sync-permissions --seed</code>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $permissions->links() }}
@endsection
