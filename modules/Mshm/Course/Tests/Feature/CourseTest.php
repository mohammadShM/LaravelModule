<?php

namespace Mshm\Course\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mshm\Category\Models\Category;
use Mshm\RolePermissions\Database\Seeds\RolePermissionTableSeeder;
use Mshm\Course\Models\Course;
use Mshm\RolePermissions\Models\Permission;
use Mshm\User\Models\User;
use Tests\TestCase;

class CourseTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    // ************************* permitted user can see curses index *************************
    public function test_permitted_user_can_see_courses_index()
    {
        // for user by permission
        $this->actAsAdmin();
        $this->get(route("courses.index"))->assertOk();
        // for super admin
        $this->actAsSuperAdmin();
        $this->get(route("courses.index"))->assertOk();
    }

    public function test_normal_user_can_not_see_courses_index()
    {
        $this->actAsUser();
        $this->get(route("courses.index"))->assertStatus(403);
    }

    // ************************* permitted user can create course *************************
    public function test_permitted_user_can_create_course()
    {
        // for user by permission
        $this->actAsAdmin();
        $this->get(route("courses.create"))->assertOk();
        // for user by permission manage own course
        $this->actAsUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->get(route("courses.create"))->assertOk();
        // for super admin
        $this->actAsSuperAdmin();
        $this->get(route("courses.create"))->assertOk();
    }

    public function test_normal_user_can_not_create_course()
    {
        $this->actAsUser();
        $this->get(route("courses.create"))->assertStatus(403);
    }

    // ************************* permitted user can store course *************************
    public function test_permitted_user_can_store_course(): void
    {
        // $this->withoutExceptionHandling();
        $this->actAsUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo([Permission::PERMISSION_MANAGE_OWN_COURSES, Permission::PERMISSION_TEACH]);
        Storage::fake('local');
        $response = $this->post(route("courses.store"), $this->courseData());
        $response->assertRedirect(route('courses.index'));
        self::assertEquals(1, Course::count());
    }

    // ************************* permitted user can edit course *************************
    public function test_permitted_user_can_edit_course()
    {
        // $this->withoutExceptionHandling();
        // for user by permission
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->get(route("courses.edit", $course->id))->assertOk();
        // for user by permission manage own course
        $this->actAsUser();
        $course = $this->createCourse();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->get(route("courses.edit", $course->id))->assertOk();
        // for super admin
        $this->actAsSuperAdmin();
        $this->get(route("courses.edit", $course->id))->assertOk();
    }

    public function test_normal_user_can_not_edit_course()
    {
        $this->actAsUser();
        $course = $this->createCourse();
        $this->get(route("courses.edit", $course->id))->assertStatus(403);
    }

    public function test_permitted_user_can_not_edit_other_users_courses()
    {
        $this->actAsUser();
        $course = $this->createCourse();
        $this->actAsUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->get(route("courses.edit", $course->id))->assertStatus(403);
    }

    // ************************* permitted user can update course *************************
    public function test_permitted_user_can_update_course()
    {
        // $this->withoutExceptionHandling();
        $this->actAsUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo([Permission::PERMISSION_MANAGE_OWN_COURSES, Permission::PERMISSION_TEACH]);
        $course = $this->createCourse();
        $this->patch(route('courses.update', $course->id), [
            'teacher_id' => auth()->id(),
            'category_id' => $course->category->id,
            'title' => "updated title",
            "slug" => "updated slug",
            "priority" => 11,
            "price" => 12001,
            "percent" => 71,
            "type" => Course::TYPE_CASH,
            "status" => Course::STATUS_NOT_COMPLETED,
           "image" => UploadedFile::fake()->image('img.jpg'),
        ])->assertRedirect(route('courses.index'));
        $course = $course->fresh();
        self::assertEquals('updated title', $course->title);
    }

    public function test_normal_user_can_not_update_course()
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->actAsUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo([Permission::PERMISSION_TEACH]);
        $this->patch(route('courses.update', $course->id), [
            'teacher_id' => auth()->id(),
            'category_id' => $course->category->id,
            'title' => "updated title",
            "slug" => "updated slug",
            "priority" => 11,
            "price" => 12001,
            "percent" => 71,
            "type" => Course::TYPE_CASH,
            "status" => Course::STATUS_NOT_COMPLETED,
            "image" => UploadedFile::fake()->image('img.jpg'),
        ])->assertStatus(403);
    }

    // ************************* permitted user can delete course *************************
    public function test_permitted_user_can_delete_course()
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->delete(route('courses.destroy', $course->id))->assertOk();
        $this->assertEquals(0, Course::count());
    }

    public function test_normal_user_can_not_delete_course()
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->actAsUser();
        $this->delete(route('courses.destroy', $course->id))->assertStatus(403);
        $this->assertEquals(1, Course::count());
    }

    // ************************* permitted user can accept course *************************
    // ************************* permitted user can reject course *************************
    // ************************* permitted user can lock course *************************
    public function test_permitted_user_can_change_confirmation_status_courses()
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->patch(route('courses.accept', $course->id), [])->assertOk();
        $this->patch(route('courses.reject', $course->id), [])->assertOk();
        $this->patch(route('courses.lock', $course->id), [])->assertOk();
    }

    public function test_normal_user_can_not_change_confirmation_status_courses()
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->actAsUser();
        $this->patch(route('courses.accept', $course->id), [])->assertStatus(403);
        $this->patch(route('courses.reject', $course->id), [])->assertStatus(403);
        $this->patch(route('courses.lock', $course->id), [])->assertStatus(403);
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
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_COURSES);
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

    // ================================ for create fake course ================================
    private function createCourse()
    {
        $data = $this->courseData() +
            ['confirmation_status' => Course::CONFIRMATION_STATUS_PENDING];
        unset($data['image']);
        return Course::create($data);
    }

    // ================================ create course fake for test ================================
    private function courseData()
    {
        $category = $this->createCategory();
        return [
            'teacher_id' => auth()->id(),
            'category_id' => $category->id,
            'title' => $this->faker->sentence(2),
            "slug" => $this->faker->sentence(2),
            "priority" => 12,
            "price" => 12000,
            "percent" => 70,
            "type" => Course::TYPE_FREE,
            "status" => Course::STATUS_COMPLETED,
            "image" => UploadedFile::fake()->image('banner.jpg'),
        ];
    }

    // ================================ create category fake for test ================================
    private function createCategory()
    {
        return Category::create([
            'title' => $this->faker->word, "slug" => $this->faker->word
        ]);
    }

}
