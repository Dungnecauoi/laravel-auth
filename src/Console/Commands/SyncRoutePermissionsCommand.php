<?php

namespace Duxbo\LaravelAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Str;
use Duxbo\LaravelAuth\Events\RouteMissingPermission;
use Duxbo\LaravelAuth\Models\Permission;

/**
 * Scans every registered route for Laravel's own `can:` middleware (or the
 * legacy `permission:` alias, for apps migrating from a custom middleware)
 * and syncs the permission names it finds into the `permissions` table.
 * Also flags named routes that declare neither, per
 * config('laravel-auth.permissions.ignored_routes').
 */
class SyncRoutePermissionsCommand extends Command
{
    protected $signature = 'laravel-auth:sync-permissions {--seed : Persist discovered permissions to the database}';

    protected $description = 'Scan registered routes for can:/permission: middleware and sync the permissions table';

    public function handle(): int
    {
        $found = collect();
        $missing = collect();

        foreach (RouteFacade::getRoutes() as $route) {
            $permission = $this->permissionFromRoute($route);

            if ($permission) {
                $found->push($permission);

                continue;
            }

            if (! $route->getName() || $this->isIgnored($route->getName())) {
                continue;
            }

            $missing->push($route->getName());
            RouteMissingPermission::dispatch($route);
        }

        $found = $found->unique()->sort()->values();

        $this->components->info("Found {$found->count()} permission(s) referenced by routes.");

        if ($this->option('seed')) {
            $found->each(fn ($name) => Permission::firstOrCreate(['name' => $name]));
            $this->components->info('Permissions table synced.');
        } elseif ($found->isNotEmpty()) {
            $this->table(['Permission'], $found->map(fn ($n) => [$n])->all());
        }

        if ($missing->isNotEmpty()) {
            $this->components->warn("{$missing->count()} named route(s) have no can:/permission: middleware:");
            $this->table(['Route name'], $missing->map(fn ($n) => [$n])->all());

            if (config('laravel-auth.permissions.require_explicit_routes')) {
                $this->components->error('Failing because laravel-auth.permissions.require_explicit_routes is enabled.');

                return self::FAILURE;
            }
        }

        return self::SUCCESS;
    }

    protected function permissionFromRoute(Route $route): ?string
    {
        foreach ($route->gatherMiddleware() as $middleware) {
            foreach (['can:', 'permission:'] as $prefix) {
                if (Str::startsWith($middleware, $prefix)) {
                    return Str::before(Str::after($middleware, $prefix), ',');
                }
            }
        }

        return null;
    }

    protected function isIgnored(string $name): bool
    {
        foreach ((array) config('laravel-auth.permissions.ignored_routes', []) as $pattern) {
            if (Str::is($pattern, $name)) {
                return true;
            }
        }

        return false;
    }
}
