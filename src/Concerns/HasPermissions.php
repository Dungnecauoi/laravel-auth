<?php

namespace Duxbo\LaravelAuth\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Duxbo\LaravelAuth\Models\Permission;

/**
 * Add to your app's User model: `use HasPermissions;`
 *
 * Permission resolution order: direct grant on the user, then via any of
 * the user's roles. Gate::before (registered in the ServiceProvider) makes
 * `$user->can('x')` and `->middleware('can:x')` use this transparently.
 */
trait HasPermissions
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermission(string $permission): bool
    {
        return $this->allPermissionNames()->contains($permission);
    }

    public function allPermissionNames()
    {
        $key = "laravel-auth.user.{$this->getKey()}.permissions";

        // Cached as a plain array, not a Collection — some cache stores
        // (database, file) round-trip objects through PHP's serialize()/
        // unserialize(), and unserializing an Illuminate\Support\Collection
        // this early in the request lifecycle (Gate::before, on every
        // authorization check) can hit a PHP class-autoloading race that
        // throws "incomplete object" instead of just working. An array has
        // no such class to resolve.
        // Short by design: this is a defense-in-depth bound on staleness,
        // not the primary invalidation mechanism — Role/Permission model
        // events and HasRoles/HasPermissions's grant/revoke methods forget
        // this cache explicitly wherever they can. A short TTL just caps
        // how long any *missed* invalidation path (a raw DB write, a
        // mutation this package didn't anticipate) can stay wrong.
        $ttl = config('laravel-auth.permissions.cache_ttl', 60);

        $names = Cache::remember($key, now()->addSeconds($ttl), function () {
            $direct = $this->permissions()->pluck('name');
            $viaRoles = $this->roles()->with('permissions')->get()
                ->flatMap(fn ($role) => $role->permissions->pluck('name'));

            return $direct->merge($viaRoles)->unique()->values()->all();
        });

        return collect($names);
    }

    public function grantPermission(string|Permission ...$permissions): static
    {
        $ids = collect($permissions)->map(
            fn ($p) => $p instanceof Permission ? $p->id : Permission::firstOrCreate(['name' => $p])->id
        );

        $this->permissions()->syncWithoutDetaching($ids);
        $this->forgetPermissionCache();

        return $this;
    }

    public function revokePermission(string|Permission $permission): static
    {
        $id = $permission instanceof Permission ? $permission->id : Permission::where('name', $permission)->value('id');

        if ($id) {
            $this->permissions()->detach($id);
            $this->forgetPermissionCache();
        }

        return $this;
    }

    public function forgetPermissionCache(): void
    {
        Cache::forget("laravel-auth.user.{$this->getKey()}.permissions");
    }
}
