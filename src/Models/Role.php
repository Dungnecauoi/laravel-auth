<?php

namespace Duxbo\LaravelAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Duxbo\LaravelAuth\Database\Factories\RoleFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label', 'guard_name'];

    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
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

        return $this;
    }
}
