# laravel-auth

Full user management, fine-grained per-route permissions, and complete auth flows for Laravel — one backend, three delivery channels: Blade, Inertia React, and a JSON API.

Every screen — login/register/password-reset/2FA flows, profile/security (password change, 2FA setup with a real QR code, session management, account deletion), and admin users/roles/permissions CRUD — is a genuine, complete port across **both** UI-owning stacks (Blade and Inertia React), not a stripped-down secondary option.

## Install

```bash
composer require duxbo/laravel-auth
php artisan laravel-auth:install
```

The install command asks which UI stack to use (**blade**, **inertia-react**, **inertia-vue**, or **headless**), publishes the config, runs migrations, syncs permissions discovered from your routes, and optionally seeds a first admin user.

- **blade** copies real, editable controllers/views/routes straight into your app (`app/Http/Controllers/{Auth,Admin}`, `resources/views/admin/{auth,profile,users,roles,permissions}`, `routes/auth.php`, `routes/admin-auth.php`) — nothing stays hidden inside `vendor/`.
- **inertia-react** additionally asks which UI library `duxbo/laravel-react-kit` was installed with (`--ui=antd` or `--ui=shadcn` — it can't tell from the filesystem alone, both variants copy their layouts to the same destination) and copies the matching `.tsx` pages into `resources/js/Pages/{Auth,Profile,Admin}` — same "real files, not vendored" approach as Blade.
- **inertia-vue** wires up `config('laravel-auth.frontend') = inertia` and the matching routes/controllers, but the Vue page components themselves aren't scaffolded yet — a starter kit for this stack is coming; build them yourself against `routes/inertia.php` in the meantime.
- **headless** is `config('laravel-auth.frontend') = api` — no views at all, just the JSON endpoints.

Just `composer require`-ing this package without ever running `laravel-auth:install` with a UI stack keeps it fully headless: no files copied, no views, just the Actions/logic every channel calls into.

### The blade stack needs `duxbo/laravel-blade-kit`; inertia-react needs `duxbo/laravel-react-kit`

Neither UI-owning stack vendors its own component library — both only *use* the matching kit's components/layout as a **peer dependency**, resolved against whatever the host app has installed:

```bash
# blade
composer require duxbo/laravel-blade-kit
php artisan blade-kit:install

# inertia-react
composer require duxbo/laravel-react-kit
php artisan react-kit:install --ui=antd   # or --ui=shadcn
```

`laravel-auth:install` warns and skips the copy if the matching kit isn't installed — without it, the Blade `<x-layouts.auth>` tags or the React `@/Layouts/AdminLayout` import simply won't resolve to anything and every page will error. Once installed:

```php
// routes/web.php, blade stack only — inertia-react's routes/inertia.php loads
// automatically based on config('laravel-auth.frontend'), nothing to require
require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';
```

If `admin.menu` exists (i.e. the kit is installed), this package also appends a "Người dùng / Vai trò / Quyền" entry to the sidebar automatically — no file to edit for that either. Set `LARAVEL_AUTH_ADMIN_UI=false` to turn that UI off entirely (menu items and routes both), regardless of which stack renders the rest.

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
