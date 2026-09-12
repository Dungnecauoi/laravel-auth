<?php

namespace Duxbo\LaravelAuth\Actions\Admin;

use Duxbo\LaravelAuth\Models\Role;
use Illuminate\Support\Facades\Validator;

class UpdateRole
{
    public function update(Role $role, array $input): Role
    {
        $data = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$role->getKey()],
            'label' => ['nullable', 'string', 'max:255'],
            'permissions' => ['array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ])->validate();

        $role->update(['name' => $data['name'], 'label' => $data['label'] ?? null]);
        $role->syncPermissions($data['permissions'] ?? []);

        return $role;
    }
}
