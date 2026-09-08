<?php

namespace Duxbo\LaravelAuth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Duxbo\LaravelAuth\Models\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->slug(2),
            'label' => $this->faker->words(2, true),
            'guard_name' => 'web',
        ];
    }
}
