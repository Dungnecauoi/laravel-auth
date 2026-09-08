# laravel-auth

Full user management, fine-grained per-route permissions, and complete auth flows for Laravel — one backend, three delivery channels: Blade, JSON API, and Inertia.

## Install

```bash
composer require duxbo/laravel-auth
php artisan laravel-auth:install
```

The install command asks which UI stack to use (**blade**, **inertia-react**, **inertia-vue**, or **headless**), publishes the config, runs migrations, syncs permissions discovered from your routes, and optionally seeds a first admin user.

- **blade** publishes real, editable Blade files into `resources/views/vendor/laravel-auth` right away — nothing stays hidden inside `vendor/`.
- **inertia-react** / **inertia-vue** wire up `config('laravel-auth.frontend') = inertia` and the matching routes/controllers; the page components themselves are a separate starter kit still coming, so you'll wire your own `Auth/Login.jsx`/`.vue` etc. against `routes/inertia.php` for now.
- **headless** is `config('laravel-auth.frontend') = api` — no views at all, just the JSON endpoints.

To reverse it: `php artisan laravel-auth:uninstall` rolls back only this package's own migrations (scoped by path, never touches unrelated ones in the same batch) and, with `--purge`, also deletes published config/views/lang.

## How it works

- **No custom User model.** Add the provided traits to your own `App\Models\User`: `HasRoles`, `HasPermissions`, and `TwoFactorAuthenticatable` (if 2FA is enabled).
- **Permissions ride on Laravel's native Gate.** No custom `permission:` middleware is required — just use the framework's own `can:` middleware or `$user->can(...)`, and a `Gate::before` hook (registered by this package) resolves it against the user's roles/permissions.
- **Pick your frontend** via `config('laravel-auth.frontend')`: `blade`, `api`, `inertia`, or `hybrid` (blade + api together). Every channel calls the same framework-agnostic Action classes (`CreateNewUser`, `AttemptToAuthenticate`, `ResetUserPassword`), so business logic is never duplicated.
- **Everything overridable.** Publish config, views, migrations, and translations independently:

```bash
php artisan vendor:publish --tag=laravel-auth-config
php artisan vendor:publish --tag=laravel-auth-views
php artisan vendor:publish --tag=laravel-auth-migrations
php artisan vendor:publish --tag=laravel-auth-lang
```

## Features

- Registration, login/logout, password reset, email verification
- Optional two-factor authentication (requires `pragmarx/google2fa`)
- Optional social login (requires `laravel/socialite`)
- Role & permission management (`Role`, `Permission` models, many-to-many with your User model)
- `php artisan laravel-auth:sync-permissions` scans routes for `can:`/`permission:` middleware and flags routes missing one
- Audit log of auth events, driven entirely by Laravel's built-in auth events (`Login`, `Logout`, `Failed`, `Registered`, `PasswordReset`, `Verified`)
- Sanctum-backed API tokens and SPA cookie auth

## Example: route-level permissions

```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('setup/shipping-gateway-save', [ShippingGatewayController::class, 'save'])
        ->name('setup.shipping_gateway_save')
        ->middleware('can:admin.setup.shipping_gateways');
});
```

## License

MIT
