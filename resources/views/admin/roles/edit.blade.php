@extends('laravel-auth::layouts.admin')

@section('title', __('Sửa vai trò'))

@section('content')
    <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="max-w-md space-y-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        @csrf
        @method('PUT')
        @include('laravel-auth::admin.roles._form')
    </form>
@endsection
