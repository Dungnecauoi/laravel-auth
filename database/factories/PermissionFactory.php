<?php

namespace Duxbo\LaravelAuth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Duxbo\LaravelAuth\Models\Permission;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->slug(3, '.'),
            'label' => $this->faker->words(3, true),
            'guard_name' => 'web',
        ];
    }
}
