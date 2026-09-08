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

        // When true, `php artisan laravel-auth:sync-permissions` fails (exit 1)
        // if it finds routes without an explicit `can:`/`permission:` middleware
        // that also aren't in `permissions.ignored_routes` — use it in CI.
        'require_explicit_routes' => false,

        // Route names (wildcards allowed via Str::is) never flagged as "missing permission".
        'ignored_routes' => [
            'login', 'logout', 'register',
            'password.*', 'verification.*', 'two-factor.*',
            'sanctum.csrf-cookie',
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
