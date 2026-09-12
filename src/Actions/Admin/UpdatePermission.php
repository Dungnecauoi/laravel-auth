<?php

namespace Duxbo\LaravelAuth\Actions\Admin;

use Duxbo\LaravelAuth\Models\Permission;
use Illuminate\Support\Facades\Validator;

class UpdatePermission
{
    public function update(Permission $permission, array $input): Permission
    {
        $data = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,'.$permission->getKey()],
            'label' => ['nullable', 'string', 'max:255'],
        ])->validate();

        $permission->update($data);

        return $permission;
    }
}
