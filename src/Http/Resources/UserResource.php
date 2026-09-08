<?php

namespace Duxbo\LaravelAuth\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'two_factor_enabled' => method_exists($this->resource, 'twoFactorEnabled') && $this->resource->twoFactorEnabled(),
            'roles' => method_exists($this->resource, 'roles') ? $this->roles->pluck('name') : [],
            'permissions' => method_exists($this->resource, 'allPermissionNames') ? $this->allPermissionNames() : [],
        ];
    }
}
