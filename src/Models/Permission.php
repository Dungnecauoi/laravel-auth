<?php

namespace Duxbo\LaravelAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Duxbo\LaravelAuth\Database\Factories\PermissionFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label', 'guard_name'];

    protected static function newFactory(): PermissionFactory
    {
        return PermissionFactory::new();
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
