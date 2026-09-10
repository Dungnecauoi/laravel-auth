<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Frontend flavor
    |--------------------------------------------------------------------------
    |
    | Which delivery channel(s) load routes/controllers for the auth flows:
    | "blade", "api", "inertia" or "hybrid" (blade + api together). Set by
    | `php artisan laravel-auth:install` when you pick a UI stack (blade,
    | inertia-react, inertia-vue, headless). Every flavor calls the same
    | Actions classes, only the response differs.
    |
    */
    'frontend' => env('LARAVEL_AUTH_FRONTEND', 'blade'),

    /*
    |--------------------------------------------------------------------------
    | Inertia stack
    |--------------------------------------------------------------------------
    |
    | Only meaningful when frontend is "inertia"/"hybrid" — informational
    | for now (react|vue), since the page components for each stack are a
    | separate starter kit still to come; the routes/controllers already
    | work with either.
    |
    */
    'inertia_stack' => env('LARAVEL_AUTH_INERTIA_STACK', 'react'),

    /*
    |--------------------------------------------------------------------------
    | User model
    |--------------------------------------------------------------------------
    |
    | The package never ships its own User model — it attaches behaviour to
    | your app's existing model via the HasRoles / HasPermissions /
    | TwoFactorAuthenticatable traits. Point this at whatever model uses them.
    |
    */
    'user_model' => env('LARAVEL_AUTH_USER_MODEL', \App\Models\User::class),

    /*
    |--------------------------------------------------------------------------
    | Users table
    |--------------------------------------------------------------------------
    |
    | Only needed so the package's own migrations can add columns/foreign
    | keys to the right table if you've renamed it away from "users".
    |
    */
    'users_table' => env('LARAVEL_AUTH_USERS_TABLE', 'users'),

    /*
    |--------------------------------------------------------------------------
    | Guard
    |--------------------------------------------------------------------------
    */
    'guard' => env('LARAVEL_AUTH_GUARD', 'web'),

    /*
    |--------------------------------------------------------------------------
    | Route prefix / name prefix
    |--------------------------------------------------------------------------
    */
    'prefix' => env('LARAVEL_AUTH_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Toggle whole flows on/off. Disabled features do not register their
    | routes at all, so a hidden endpoint can never be hit.
    |
    */
    'features' => [
        'registration'        => env('LARAVEL_AUTH_REGISTRATION', true),
        'email_verification'  => env('LARAVEL_AUTH_EMAIL_VERIFICATION', true),
        'password_reset'      => env('LARAVEL_AUTH_PASSWORD_RESET', true),
        'two_factor'          => env('LARAVEL_AUTH_2FA', false),
        'social_login'        => env('LARAVEL_AUTH_SOCIAL', false),
        'audit_log'           => env('LARAVEL_AUTH_AUDIT_LOG', true),
        'session_management'  => env('LARAVEL_AUTH_SESSIONS', true),
        'impersonation'       => env('LARAVEL_AUTH_IMPERSONATION', false),

        // The /admin Users/Roles/Permissions management UI. Always Blade,
        // regardless of `frontend` — a staff back-office and an API/Inertia
        // frontend for end users aren't mutually exclusive.
        'admin_ui'            => env('LARAVEL_AUTH_ADMIN_UI', true),

        // Only one active login per user at a time, across every channel:
        // a fresh login revokes every other session row AND every other
        // Sanctum token for that user. See Actions/EnforceSingleSession.
        'single_session'      => env('LARAVEL_AUTH_SINGLE_SESSION', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default admin account
    |--------------------------------------------------------------------------
    |
    | Set LARAVEL_AUTH_ADMIN_EMAIL (+ password) and this account is created
    | — or updated to have the super-admin role — by:
    |   php artisan db:seed --class="Duxbo\LaravelAuth\Database\Seeders\AdminUserSeeder"
    | `laravel-auth:install` calls this too, so it works non-interactively
    | (CI, `composer run setup`, ...) with no TTY prompt at all. Leaving
    | email blank means no admin is seeded and nothing runs.
    |
    */
    'admin' => [
        'email' => env('LARAVEL_AUTH_ADMIN_EMAIL'),
        'password' => env('LARAVEL_AUTH_ADMIN_PASSWORD'),
        'name' => env('LARAVEL_AUTH_ADMIN_NAME', 'Administrator'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social providers
    |--------------------------------------------------------------------------
    |
    | Only used when features.social_login is true and laravel/socialite is
    | installed. Each entry must also exist under config('services').
    |
    */
    'social_providers' => explode(',', (string) env('LARAVEL_AUTH_SOCIAL_PROVIDERS', '')),

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        // Role name(s) that always pass every permission check (Gate::before).
        'super_admin_roles' => ['super-admin'],

        // UserPolicy/RolePolicy/PermissionPolicy check these exact strings —
        // route scanning can't discover them on its own (a `can:viewAny,User`
        // middleware only reveals the generic ability "viewAny", not which
        // resource's policy it resolves to). `sync-permissions --seed`
        // always includes this list so the admin Roles page has something
        // real to assign, out of the box.
        'builtin' => [
            'laravel-auth.users.viewAny', 'laravel-auth.users.view', 'laravel-auth.users.create', 'laravel-auth.users.update', 'laravel-auth.users.delete',
            'laravel-auth.roles.viewAny', 'laravel-auth.roles.view', 'laravel-auth.roles.create', 'laravel-auth.roles.update', 'laravel-auth.roles.delete',
            'laravel-auth.permissions.viewAny', 'laravel-auth.permissions.view', 'laravel-auth.permissions.create', 'laravel-auth.permissions.update', 'laravel-auth.permissions.delete',
        ],

        // When true, `php artisan laravel-auth:sync-permissions` fails (exit 1)
        // if it finds routes without an explicit `can:`/`permission:` middleware
        // that also aren't in `permissions.ignored_routes` — use it in CI.
        'require_explicit_routes' => false,

        // Route names (wildcards allowed via Str::is) never flagged as "missing permission".
        'ignored_routes' => [
            'login', 'logout', 'register',
            'password.*', 'verification.*', 'two-factor.*',
            'sanctum.csrf-cookie',
            // "Manage my own account" routes — auth is enough, no permission needed.
            'profile.*', 'sessions.*',
            // Genuinely can:-protected (see permissions.builtin above) — the
            // scanner just can't read a policy-resolved ability's real slug
            // out of `can:viewAny,Model` the way it can a bare `can:some.slug`.
            'admin.users.*', 'admin.roles.*', 'admin.permissions.*',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirects
    |--------------------------------------------------------------------------
    */
    'redirects' => [
        'home'   => '/dashboard',
        'login'  => '/login',
    ],

];
