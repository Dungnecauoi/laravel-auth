<?php

namespace Duxbo\LaravelAuth\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Duxbo\LaravelAuth\Models\Role;

/**
 * Add to your app's User model: `use HasRoles;`
 */
trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string|array $role): bool
    {
        $roles = is_array($role) ? $role : [$role];

        return $this->roles()->whereIn('name', $roles)->exists()
            || $this->roles->pluck('name')->intersect($roles)->isNotEmpty();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->hasRole($roles);
    }

    public function assignRole(string|Role ...$roles): static
    {
        $ids = collect($roles)->map(
            fn ($role) => $role instanceof Role ? $role->id : Role::firstOrCreate(['name' => $role])->id
        );

        $this->roles()->syncWithoutDetaching($ids);
        $this->forgetPermissionCacheIfSupported();

        return $this;
    }

    public function removeRole(string|Role $role): static
    {
        $id = $role instanceof Role ? $role->id : Role::where('name', $role)->value('id');

        if ($id) {
            $this->roles()->detach($id);
            $this->forgetPermissionCacheIfSupported();
        }

        return $this;
    }

    public function syncRoles(array $roles): static
    {
        $ids = collect($roles)->map(
            fn ($role) => $role instanceof Role ? $role->id : Role::firstOrCreate(['name' => $role])->id
        );

        $this->roles()->sync($ids);
        $this->forgetPermissionCacheIfSupported();

        return $this;
    }

    protected function forgetPermissionCacheIfSupported(): void
    {
        if (method_exists($this, 'forgetPermissionCache')) {
            $this->forgetPermissionCache();
        }
    }
}
