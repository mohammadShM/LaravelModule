<?php

namespace Mshm\RolePermissions\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mshm\RolePermissions\Database\Seeds\RolePermissionTableSeeder;
use Mshm\RolePermissions\Models\Permission;
use Mshm\RolePermissions\Models\Role;
use Mshm\User\Models\User;
use Tests\TestCase;

class RolesTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase;

    public function test_permitted_user_can_see_index()
    {
        $this->actAsAdmin();
        $this->get(route('role-permissions.index'))->assertOk();
        $this->actAsSuperAdmin();
        $this->get(route('role-permissions.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_index()
    {
        $this->actAsUser();
        $this->get(route('role-permissions.index'))->assertStatus(403);
    }

    public function test_permitted_user_can_store_roles()
    {
        $this->actAsAdmin();
        $this->post(route('role-permissions.store'), [
            "name" => "test",
            "permissions" => [
                Permission::PERMISSION_MANAGE_COURSES,
                Permission::PERMISSION_TEACH,
            ],
        ])->assertRedirect(route('role-permissions.index'));
        $this->assertEquals(count(Role::$roles) + 1, Role::count());
    }

    public function test_normal_user_can_not_store_roles()
    {
        $this->actAsUser();
        $this->post(route('role-permissions.store'), [
            "name" => "test",
            "permissions" => [
                Permission::PERMISSION_MANAGE_COURSES,
                Permission::PERMISSION_TEACH,
            ],
        ])->assertStatus(403);
        $this->assertEquals(count(Role::$roles), Role::count());
    }

    public function test_permitted_user_can_see_edit()
    {
        $this->actAsAdmin();
        $role = $this->createRole();
        $this->get(route('role-permissions.edit', $role->id))->assertOk();
        $this->actAsSuperAdmin();
        $this->get(route('role-permissions.edit', $role->id))->assertOk();
    }

    public function test_normal_user_can_not_see_edit()
    {
        $this->actAsUser();
        $role = $this->createRole();
        $this->get(route('role-permissions.edit', $role->id))->assertStatus(403);
    }

    public function test_permitted_user_can_update_roles()
    {
        $this->actAsAdmin();
        $role = $this->createRole();
        $this->patch(route('role-permissions.update', $role->id), [
            "id" => $role->id,
            "name" => "test21",
            "permissions" => [
                Permission::PERMISSION_TEACH,
            ],
        ])->assertRedirect(route('role-permissions.index'));
        $this->assertEquals("test21", $role->fresh()->name);
    }

    public function test_normal_user_can_not_update_roles()
    {
        $this->actAsUser();
        $role = $this->createRole();
        $this->patch(route('role-permissions.update', $role->id), [
            "id" => $role->id,
            "name" => "test21",
            "permissions" => [
                Permission::PERMISSION_TEACH,
            ],
        ])->assertStatus(403);
        $this->assertEquals($role->name, $role->fresh()->name);
    }

    public function test_permitted_user_can_delete_role()
    {
        $this->actAsAdmin();
        $role = $this->createRole();
        $this->delete(route('role-permissions.destroy', $role->id))->assertOk();
        $this->assertEquals(count(Role::$roles), Role::count());
    }

    public function test_normal_user_can_not_delete_role()
    {
        $this->actAsUser();
        $role = $this->createRole();
        $this->delete(route('role-permissions.destroy', $role->id))->assertStatus(403);
        $this->assertEquals(count(Role::$roles) + 1, Role::count());
    }

    // ================================ for create fake normal user ================================
    private function actAsUser()
    {
        $this->createUser();
    }

    // ================================ for create fake admin ================================
    private function actAsAdmin()
    {
        $this->createUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_ROLE_PERMISSION);
    }

    // ================================ for create fake admin ================================
    private function actAsSuperAdmin()
    {
        $this->createUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_SUPER_ADMIN);
    }

    // ================================ for create fake user ================================
    private function createUser()
    {
        $this->actingAs(User::factory()->create());
        $this->seed(RolePermissionTableSeeder::class);
    }

    // ================================ for create fake user ================================
    private function createRole()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return Role::create(['name' => 'test',])->syncPermissions([
            Permission::PERMISSION_MANAGE_COURSES,
            Permission::PERMISSION_TEACH,
        ]);
    }

}
