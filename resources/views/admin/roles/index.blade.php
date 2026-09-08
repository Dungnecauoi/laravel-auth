@extends('laravel-auth::layouts.admin')

@section('title', __('Vai trò'))

@section('actions')
    <a href="{{ route('admin.roles.create') }}" class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
        {{ __('Thêm vai trò') }}
    </a>
@endsection

@section('content')
    <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead>
                <tr class="text-left text-gray-500">
                    <th class="px-4 py-3 font-medium">{{ __('Tên') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('Số người dùng') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($roles as $role)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $role->label ?? $role->name }} <span class="font-normal text-gray-400">({{ $role->name }})</span></td>
                        <td class="px-4 py-3 text-gray-600">{{ $role->users_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.roles.edit', $role) }}" class="font-medium text-indigo-600 hover:text-indigo-500">{{ __('Sửa') }}</a>
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="ml-3 inline" onsubmit="return confirm('{{ __('Xoá vai trò này?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 hover:text-red-500">{{ __('Xoá') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">{{ __('Chưa có vai trò nào.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $roles->links() }}
@endsection
