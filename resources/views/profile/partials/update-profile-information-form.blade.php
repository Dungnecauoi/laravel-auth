<form method="POST" action="{{ route('profile.update') }}" class="max-w-md space-y-4">
    @csrf
    @method('PATCH')

    <x-admin.input
        label="{{ __('Họ tên') }}" name="name" value="{{ old('name', $user->name) }}"
        :error="$errors->first('name')" required
    />

    <div>
        <x-admin.input
            label="{{ __('Email') }}" name="email" type="email" value="{{ old('email', $user->email) }}"
            :error="$errors->first('email')" required
        />

        @if (config('laravel-auth.features.email_verification') && method_exists($user, 'hasVerifiedEmail') && ! $user->hasVerifiedEmail())
            <p class="mt-1 text-sm text-warning-600">{{ __('Email chưa được xác minh.') }}</p>
        @endif
    </div>

    <x-admin.button type="submit">{{ __('Lưu') }}</x-admin.button>
</form>
