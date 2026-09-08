@extends('laravel-auth::layouts.admin')

@section('title', __('Người dùng'))

@section('actions')
    <a href="{{ route('admin.users.create') }}" class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
        {{ __('Thêm người dùng') }}
    </a>
@endsection

@section('content')
    <form method="GET" class="max-w-sm">
        <input
            type="search" name="q" value="{{ $search }}" placeholder="{{ __('Tìm theo tên hoặc email...') }}"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
        >
    </form>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead>
                <tr class="text-left text-gray-500">
                    <th class="px-4 py-3 font-medium">{{ __('Tên') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('Email') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('Vai trò') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            @foreach ($user->roles as $role)
                                <span class="mr-1 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">{{ $role->label ?? $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.users.edit', $user) }}" class="font-medium text-indigo-600 hover:text-indigo-500">{{ __('Sửa') }}</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="ml-3 inline" onsubmit="return confirm('{{ __('Xoá người dùng này?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 hover:text-red-500">{{ __('Xoá') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">{{ __('Chưa có người dùng nào.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
@endsection
