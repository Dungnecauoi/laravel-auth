@php
    $permission = $permission ?? null;
@endphp

<div>
    <label for="name" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Tên (định danh)') }}</label>
    <input
        id="name" type="text" name="name" value="{{ old('name', $permission->name ?? '') }}" required autofocus placeholder="admin.setup.shipping_gateways"
        class="block w-full max-w-md rounded-md border-0 py-1.5 font-mono text-sm text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:leading-6 {{ $errors->has('name') ? 'ring-red-300 focus:ring-red-500' : 'ring-gray-300 focus:ring-indigo-600' }}"
    >
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="label" class="mb-1 block text-sm font-medium text-gray-900">{{ __('Nhãn hiển thị') }}</label>
    <input
        id="label" type="text" name="label" value="{{ old('label', $permission->label ?? '') }}"
        class="block w-full max-w-md rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
    >
</div>

<button type="submit" class="rounded-md bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
    {{ __('Lưu') }}
</button>
