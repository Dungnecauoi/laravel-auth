@extends('laravel-auth::layouts.admin')

@section('title', __('Sửa quyền'))

@section('content')
    <form method="POST" action="{{ route('admin.permissions.update', $permission) }}" class="max-w-md space-y-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        @csrf
        @method('PUT')
        @include('laravel-auth::admin.permissions._form')
    </form>
@endsection
