@php
    $permission = $permission ?? null;
@endphp

<x-admin.input
    label="{{ __('Tên (định danh)') }}" name="name" value="{{ old('name', $permission->name ?? '') }}"
    :error="$errors->first('name')" required autofocus placeholder="admin.setup.shipping_gateways"
/>

<x-admin.input
    label="{{ __('Nhãn hiển thị') }}" name="label" value="{{ old('label', $permission->label ?? '') }}"
/>

<x-admin.button type="submit">{{ __('Lưu') }}</x-admin.button>
