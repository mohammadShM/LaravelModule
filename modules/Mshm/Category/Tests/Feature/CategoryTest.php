<?php

namespace Mshm\Category\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mshm\Category\Models\Category;
use Mshm\Course\Database\Seeds\RolePermissionTableSeeder;
use Mshm\RolePermissions\Models\Permission;
use Mshm\User\Models\User;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function test_permitted_user_can_see_categories_panel()
    {
        $this->actionAsAdmin();
        $this->assertAuthenticated();
        $this->seed(RolePermissionTableSeeder::class);
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_CATEGORIES);
        $this->get(route('categories.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_categories_panel()
    {
        $this->actionAsAdmin();
        $this->get(route('categories.index'))->assertStatus(403);
    }

    public function test_permitted_user_can_create_category()
    {
        $this->withoutExceptionHandling();
        $this->actionAsAdmin();
        $this->seed(RolePermissionTableSeeder::class);
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_CATEGORIES);
        $this->createCategory();
        $this->assertEquals(1, Category::all()->count());
    }

    public function test_permitted_user_can_update_category()
    {
        $newTitle = "moh131313";
        $this->actionAsAdmin();
        $this->seed(RolePermissionTableSeeder::class);
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_CATEGORIES);
        $this->createCategory();
        $this->assertEquals(1, Category::all()->count());
        $this->patch(route('categories.update', 1),
            ['title' => $newTitle, "slug" => $this->faker->word]);
        $this->assertEquals(1, Category::whereTitle($newTitle)->count());
    }

    public function test_user_can_delete_category()
    {
        $this->actionAsAdmin();
        $this->seed(RolePermissionTableSeeder::class);
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_CATEGORIES);
        $this->createCategory();
        $this->assertEquals(1, Category::all()->count());
        $this->delete(route('categories.destroy', 1))->assertOk();
    }

    // ================================== create admin fake for test ==================================
    private function actionAsAdmin()
    {
        $this->actingAs(factory(User::class)->create());
    }
    // ================================== create admin fake for test ==================================

    // ================================== create category fake for test ================================
    private function createCategory()
    {
        $this->post(route('categories.store'),
            ['title' => $this->faker->word, "slug" => $this->faker->word]);
    }
    // ================================== create category fake for test ================================

}
