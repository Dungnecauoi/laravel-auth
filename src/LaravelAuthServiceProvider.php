<?php

namespace Duxbo\LaravelAuth;

use Closure;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Duxbo\LaravelAuth\Console\Commands\InstallCommand;
use Duxbo\LaravelAuth\Console\Commands\SyncRoutePermissionsCommand;
use Duxbo\LaravelAuth\Console\Commands\UninstallCommand;
use Duxbo\LaravelAuth\Listeners\AuditAuthEvents;
use Duxbo\LaravelAuth\Models\Permission;
use Duxbo\LaravelAuth\Models\Role;
use Duxbo\LaravelAuth\Policies\PermissionPolicy;
use Duxbo\LaravelAuth\Policies\RolePolicy;
use Duxbo\LaravelAuth\Policies\UserPolicy;
use LaravelCore\Menu\MenuRegistry;

class LaravelAuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-auth.php', 'laravel-auth');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-auth');
        $this->loadRoutesFrom_ForFlavor();
        $this->registerAdminAuthMiddleware();

        $this->registerGateIntegration();
        $this->registerPolicies();
        $this->registerAuditLog();
        $this->registerAdminMenu();
        $this->registerMenuPermissionFilter();

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                UninstallCommand::class,
                SyncRoutePermissionsCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/laravel-auth.php' => config_path('laravel-auth.php'),
            ], 'laravel-auth-config');

            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'laravel-auth-migrations');

            $this->publishes([
                __DIR__.'/../resources/lang' => $this->app->langPath('vendor/laravel-auth'),
            ], 'laravel-auth-lang');
        }
    }

    /**
     * Which route file(s) get registered depends on config('laravel-auth.frontend'):
     * "api"/"inertia" load their own file. "blade" (the default) loads
     * nothing from this package at all — the Blade UI (login, register,
     * 2FA, profile, admin users/roles/permissions) lives entirely in
     * duxbo/laravel-blade-kit's own routes/auth.php and routes/admin.php,
     * calling this package's Actions classes directly. This package is
     * headless: no views, no Blade-flavor routes, only logic. "hybrid" is
     * shorthand for "blade,api" (a normal website plus a token API for
     * e.g. a mobile app) — any other comma-separated combination works
     * too, EXCEPT "blade,inertia" together: both would register the same
     * page URIs (login, register, ...) and silently shadow one another,
     * since a browser page is either server-rendered or an SPA shell,
     * never both.
     */
    protected function loadRoutesFor(): array
    {
        $flavor = config('laravel-auth.frontend', 'blade');

        $flavors = $flavor === 'hybrid' ? ['blade', 'api'] : explode(',', $flavor);

        return collect($flavors)
            ->map(fn ($f) => trim($f) === 'blade' ? 'web' : trim($f))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function loadRoutesFrom_ForFlavor(): void
    {
        foreach ($this->loadRoutesFor() as $flavor) {
            $file = __DIR__."/../routes/{$flavor}.php";

            if ($flavor === 'inertia' && ! class_exists(\Inertia\Inertia::class)) {
                continue; // avoid hard dependency on inertiajs/inertia-laravel
            }

            if (! file_exists($file)) {
                continue; // "web" (blade) has no file here on purpose — see loadRoutesFor()'s docblock
            }

            // Deliberately no name/prefix group: routes keep Laravel's own
            // conventional names (login, register, password.reset, ...)
            // so `route('login')`, the default Authenticate middleware,
            // and any other package that assumes those names keep working.
            //
            // The "web" group, however, IS added explicitly here: routes
            // registered via loadRoutesFrom() inside a ServiceProvider never
            // get it automatically the way the host app's own routes/web.php
            // does — without it, sessions, CSRF verification, and $errors
            // simply don't exist on these routes. routes/api.php wraps
            // itself in the "api" group instead, so it's left alone here.
            if (in_array($flavor, ['web', 'inertia'], true)) {
                Route::middleware('web')->group(fn () => $this->loadRoutesFrom($file));
            } else {
                $this->loadRoutesFrom($file);
            }
        }
    }

    /**
     * Single integration point with Laravel's native Gate: `$user->can()`,
     * `@can`, `Gate::authorize()` and the `can:` middleware all funnel
     * through Gate::before, so no custom middleware is required for
     * permission checks — only the standard Laravel authorization flow.
     */
    protected function registerGateIntegration(): void
    {
        Gate::before(function ($user, string $ability) {
            if (! method_exists($user, 'hasRole')) {
                return null;
            }

            foreach ((array) config('laravel-auth.permissions.super_admin_roles', []) as $role) {
                if ($role && $user->hasRole($role)) {
                    return true;
                }
            }

            if (method_exists($user, 'hasPermission') && $user->hasPermission($ability)) {
                return true;
            }

            return null;
        });
    }

    protected function registerPolicies(): void
    {
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(config('laravel-auth.user_model'), UserPolicy::class);
    }

    protected function registerAuditLog(): void
    {
        if (config('laravel-auth.features.audit_log')) {
            Event::subscribe(AuditAuthEvents::class);
        }
    }

    /**
     * Every kit's admin routes (duxbo/laravel-blade-kit, duxbo/laravel-react-kit)
     * carry ->middleware('admin.auth') — a neutral alias duxbo/laravel-core
     * registers as a pass-through by default, so an app works before any
     * auth package is installed. Overriding the exact same alias here is
     * what actually turns login enforcement on: neither the kit nor this
     * package needs to know the other exists, only the alias name.
     */
    protected function registerAdminAuthMiddleware(): void
    {
        $this->app['router']->aliasMiddleware('admin.auth', \Illuminate\Auth\Middleware\Authenticate::class);
    }

    /**
     * Registered through duxbo/laravel-core's MenuRegistry rather than
     * mutating config('admin.menu') directly — that older approach only
     * ever worked for duxbo/laravel-blade-kit (the only kit that reads
     * that config key) and silently did nothing for any other kit. Every
     * kit reads MenuRegistry, so this now reaches all of them the same
     * way, whether or not a UI for it exists yet — see registerMenuPermissionFilter()
     * for how items carrying a 'permission' key get hidden from users who
     * lack it.
     */
    protected function registerAdminMenu(): void
    {
        if (! config('laravel-auth.features.admin_ui')) {
            return;
        }

        $menu = $this->app->make(MenuRegistry::class);

        $menu->register([
            'key' => 'laravel-auth.accounts',
            'label' => 'Quản lý tài khoản',
            'icon' => 'users',
        ]);

        $menu->register([
            'label' => 'Người dùng',
            'icon' => 'users',
            'route' => 'admin.users.index',
            'permission' => 'laravel-auth.users.viewAny',
        ], parentKey: 'laravel-auth.accounts');

        $menu->register([
            'label' => 'Vai trò',
            'icon' => 'settings',
            'route' => 'admin.roles.index',
            'permission' => 'laravel-auth.roles.viewAny',
        ], parentKey: 'laravel-auth.accounts');

        $menu->register([
            'label' => 'Quyền',
            'icon' => 'settings',
            'route' => 'admin.permissions.index',
            'permission' => 'laravel-auth.permissions.viewAny',
        ], parentKey: 'laravel-auth.accounts');
    }

    /**
     * The one filter every 'permission'-carrying menu item — from this
     * package or any other — is checked against. Registered once here
     * because this is the only package that knows what a "permission"
     * means; MenuRegistry itself stays permission-agnostic. Items with no
     * 'permission' key (most of a typical menu) are left untouched.
     */
    protected function registerMenuPermissionFilter(): void
    {
        $this->app->make(MenuRegistry::class)->filter(function (array $items, Closure $next) {
            return $next(array_values(array_filter(
                $items,
                fn (array $item) => ! isset($item['permission']) || Gate::allows($item['permission']),
            )));
        });
    }
}
