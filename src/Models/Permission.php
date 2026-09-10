<?php

namespace Duxbo\LaravelAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Duxbo\LaravelAuth\Database\Factories\PermissionFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label', 'guard_name'];

    protected static function newFactory(): PermissionFactory
    {
        return PermissionFactory::new();
    }

    protected static function booted(): void
    {
        // Deleting a permission revokes it everywhere it was granted — both
        // directly and via any role — so every affected user's cached
        // HasPermissions::allPermissionNames() needs invalidating too,
        // otherwise the (now-deleted) permission keeps "working" for them
        // for up to its TTL.
        static::deleting(function (self $permission) {
            $userKey = fn () => $permission->users()->getRelated()->getKeyName();

            $permission->users()->pluck($userKey())
                ->each(fn ($id) => Cache::forget("laravel-auth.user.{$id}.permissions"));

            $permission->roles()->with('users')->get()
                ->flatMap(fn (Role $role) => $role->users)
                ->pluck($userKey())
                ->unique()
                ->each(fn ($id) => Cache::forget("laravel-auth.user.{$id}.permissions"));
        });
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('laravel-auth.user_model'));
    }
}
