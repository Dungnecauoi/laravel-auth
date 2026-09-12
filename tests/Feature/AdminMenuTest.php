<?php

namespace Duxbo\LaravelAuth\Tests\Feature;

use Duxbo\LaravelAuth\Models\Permission;
use Duxbo\LaravelAuth\Tests\TestCase;
use Duxbo\LaravelAuth\Tests\TestUser;
use Illuminate\Support\Facades\Hash;
use LaravelCore\Menu\MenuRegistry;

class AdminMenuTest extends TestCase
{
    public function test_it_registers_the_accounts_group_and_its_children(): void
    {
        $menu = $this->app->make(MenuRegistry::class);

        $this->assertSame(
            ['laravel-auth.accounts'],
            array_column($menu->items(), 'key'),
        );

        $this->assertSame(
            ['Người dùng', 'Vai trò', 'Quyền'],
            array_column($menu->childrenFor('laravel-auth.accounts'), 'label'),
        );
    }

    public function test_a_user_without_the_matching_permission_does_not_see_that_item(): void
    {
        $user = $this->actingAsUserWithoutPermissions();

        $children = $this->app->make(MenuRegistry::class)->childrenFor('laravel-auth.accounts');

        $this->assertSame([], $children);
    }

    public function test_a_user_with_only_one_permission_sees_only_that_item(): void
    {
        $user = $this->actingAsUserWithoutPermissions();
        $user->grantPermission(Permission::create(['name' => 'laravel-auth.roles.viewAny']));
        $user->forgetPermissionCache();

        $children = $this->app->make(MenuRegistry::class)->childrenFor('laravel-auth.accounts');

        $this->assertSame(['Vai trò'], array_column($children, 'label'));
    }

    public function test_a_super_admin_sees_every_item_regardless_of_granted_permissions(): void
    {
        $user = TestUser::create([
            'name' => 'Root',
            'email' => 'root@example.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole(\Duxbo\LaravelAuth\Models\Role::create(['name' => 'super-admin']));
        $this->actingAs($user);

        $children = $this->app->make(MenuRegistry::class)->childrenFor('laravel-auth.accounts');

        $this->assertSame(['Người dùng', 'Vai trò', 'Quyền'], array_column($children, 'label'));
    }

    public function test_nothing_is_registered_when_the_admin_ui_feature_is_disabled(): void
    {
        config(['laravel-auth.features.admin_ui' => false]);

        // Provider boot() already ran once with the feature on (Testbench
        // boots providers before the test method body runs) -- re-invoke
        // registerAdminMenu() directly via reflection against a fresh
        // MenuRegistry to prove the guard clause itself, independent of
        // that earlier boot-time call.
        $menu = new MenuRegistry();
        $this->app->instance(MenuRegistry::class, $menu);

        $provider = new \Duxbo\LaravelAuth\LaravelAuthServiceProvider($this->app);
        $method = new \ReflectionMethod($provider, 'registerAdminMenu');
        $method->invoke($provider);

        $this->assertSame([], $menu->items());
    }

    private function actingAsUserWithoutPermissions(): TestUser
    {
        $user = TestUser::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user);

        return $user;
    }
}
