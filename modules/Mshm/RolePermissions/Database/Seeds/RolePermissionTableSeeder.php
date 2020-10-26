<?php

namespace Mshm\RolePermissions\Database\Seeds;

use Illuminate\Database\Seeder;
use Mshm\RolePermissions\Models\Permission;
use Mshm\RolePermissions\Models\Role;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Permission::$permissions as $permission) {
            Permission::findOrCreate($permission);
        }
        foreach (Role::$roles as $name => $permissions) {
            /** @noinspection PhpUndefinedMethodInspection */
            Role::findOrCreate($name)->givePermissionTo($permissions);
        }
    }

}
