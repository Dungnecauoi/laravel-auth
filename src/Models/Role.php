<?php

namespace Duxbo\LaravelAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Duxbo\LaravelAuth\Database\Factories\RoleFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label', 'guard_name'];

    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
    }

    protected static function booted(): void
    {
        // A role being deleted implicitly revokes every permission it
        // granted its members — without this, they'd keep the cached
        // (now-phantom) access for up to allPermissionNames()'s TTL.
        static::deleting(fn (self $role) => $role->forgetMembersPermissionCache());
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('laravel-auth.user_model'));
    }

    public function givePermissionTo(string|Permission ...$permissions): static
    {
        $ids = collect($permissions)->map(
            fn ($p) => $p instanceof Permission ? $p->id : Permission::firstOrCreate(['name' => $p])->id
        );

        $this->permissions()->syncWithoutDetaching($ids);
        $this->forgetMembersPermissionCache();

        return $this;
    }

    /**
     * Use this instead of calling $role->permissions()->sync() directly
     * (e.g. from an admin "edit role" form) — a plain sync() changes the
     * pivot rows but leaves every member's cached HasPermissions::
     * allPermissionNames() pointing at the old set for up to its TTL.
     */
    public function syncPermissions(array $permissionIds): static
    {
        $this->permissions()->sync($permissionIds);
        $this->forgetMembersPermissionCache();

        return $this;
    }

    public function forgetMembersPermissionCache(): void
    {
        $this->users()->pluck($this->users()->getRelated()->getKeyName())
            ->each(fn ($id) => Cache::forget("laravel-auth.user.{$id}.permissions"));
    }
}
