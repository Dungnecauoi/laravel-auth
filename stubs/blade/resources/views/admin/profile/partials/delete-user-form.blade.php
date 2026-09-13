<p class="max-w-md text-sm text-neutral-500">
    {{ __('Một khi tài khoản bị xoá, toàn bộ dữ liệu sẽ bị xoá vĩnh viễn. Vui lòng tải xuống dữ liệu bạn muốn giữ lại trước khi tiếp tục.') }}
</p>

<x-admin.button type="button" variant="danger" class="mt-4" @click="$dispatch('open-modal', 'delete-account')">
    {{ __('Xoá tài khoản') }}
</x-admin.button>

<x-admin.modal id="delete-account" :title="__('Xoá tài khoản')">
    <p class="text-sm text-neutral-600">{{ __('Nhập mật khẩu để xác nhận.') }}</p>

    <form id="delete-account-form" method="POST" action="{{ route('profile.destroy') }}" class="mt-4">
        @csrf
        @method('DELETE')
        <x-admin.input
            label="{{ __('Mật khẩu') }}" name="password" type="password"
            :error="$errors->first('password')" autocomplete="current-password"
        />
    </form>

    <x-slot:footer>
        <x-admin.button type="button" variant="secondary" @click="$dispatch('close-modal', 'delete-account')">{{ __('Huỷ') }}</x-admin.button>
        <x-admin.button type="submit" form="delete-account-form" variant="danger">{{ __('Xác nhận xoá') }}</x-admin.button>
    </x-slot:footer>
</x-admin.modal>
