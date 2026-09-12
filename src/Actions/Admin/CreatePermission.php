<?php

namespace Duxbo\LaravelAuth\Actions\Admin;

use Duxbo\LaravelAuth\Models\Permission;
use Illuminate\Support\Facades\Validator;

class CreatePermission
{
    public function create(array $input): Permission
    {
        $data = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'label' => ['nullable', 'string', 'max:255'],
        ])->validate();

        return Permission::create($data);
    }
}
