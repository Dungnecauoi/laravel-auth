@extends('laravel-auth::layouts.admin')

@section('title', __('Thêm quyền'))

@section('content')
    <form method="POST" action="{{ route('admin.permissions.store') }}" class="max-w-md space-y-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        @csrf
        @include('laravel-auth::admin.permissions._form')
    </form>
@endsection
