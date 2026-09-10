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

### The blade stack needs `dungnecauoi/laravel-blade-kit`

Every Blade view this package ships (`<x-layouts.auth>`, `<x-layouts.admin>`, `<x-admin.input>`, `<x-admin.table>`, ...) is a **peer dependency** on [laravel-blade-kit](https://github.com/Dungnecauoi/laravel-blade-kit) — it doesn't vendor a copy, it just uses the tags, resolved against whatever the host app has installed:

```bash
composer require dungnecauoi/laravel-blade-kit
php artisan blade-kit:install
```

`laravel-auth:install` warns if it's missing when you pick the blade stack. Without it, `<x-layouts.auth>` etc. simply won't resolve to anything and every auth page will error. If `admin.menu` exists (i.e. Blade Kit is installed), this package also appends a "Người dùng / Vai trò / Quyền" entry to the sidebar automatically — no Blade file to edit for that either.

To reverse it: `php artisan laravel-auth:uninstall` rolls back only this package's own migrations (scoped by path, never touches unrelated ones in the same batch) and, with `--purge`, also deletes published config/views/lang.

### Default admin account (no TTY needed)

Set these in `.env` and the admin account is ready after a plain, non-interactive `migrate` + seed — useful for CI/deploy pipelines where `laravel-auth:install`'s prompts aren't an option:

```env
LARAVEL_AUTH_ADMIN_EMAIL=admin@example.com
LARAVEL_AUTH_ADMIN_PASSWORD=change-me
LARAVEL_AUTH_ADMIN_NAME="Administrator"
```

```bash
php artisan db:seed --class="Duxbo\LaravelAuth\Database\Seeders\AdminUserSeeder"
```

It's idempotent (`firstOrCreate` on email) and assigns the configured `permissions.super_admin_roles`. `laravel-auth:install` calls the same seeder, so both paths behave identically. Leave `LARAVEL_AUTH_ADMIN_EMAIL` empty and nothing is seeded.

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
- Profile page: update name/email, change password, delete account (behind Laravel's native `password.confirm` middleware)
- Two-factor authentication: enable/QR/confirm/disable/recovery codes (requires `pragmarx/google2fa` + `bacon/bacon-qr-code`)
- Session management: list & revoke active sessions (requires the `database` session driver)
- `features.single_session`: a fresh login revokes every other session row *and* Sanctum token for that user, across every channel at once
- Admin UI at `/admin` for Users, Roles, and Permissions — CRUD, role/permission assignment, authorized entirely via `can:` route middleware
- Optional social login (requires `laravel/socialite`)
- `php artisan laravel-auth:sync-permissions` scans routes for `can:`/`permission:` middleware and flags routes missing one
- Audit log of auth events, driven entirely by Laravel's built-in auth events (`Login`, `Logout`, `Failed`, `Registered`, `PasswordReset`, `Verified`)
- Sanctum-backed API tokens and SPA cookie auth

## Example: route-level permissions

```php
Route::middleware('auth')->group(function () {
    Route::post('setup/shipping-gateway-save', [ShippingGatewayController::class, 'save'])
        ->name('setup.shipping_gateway_save')
        ->middleware('can:admin.setup.shipping_gateways');
});
```

A user with one of `permissions.super_admin_roles` bypasses every `can:` check automatically (see `Gate::before` in the ServiceProvider) — there's no separate `role:` middleware to reach for.

## License

MIT
