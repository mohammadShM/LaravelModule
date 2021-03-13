<?php

namespace Mshm\Course\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Mshm\Category\Models\Category;
use Mshm\Course\Models\Course;
use Mshm\Course\Models\Lesson;
use Mshm\RolePermissions\Database\Seeds\RolePermissionTableSeeder;
use Mshm\RolePermissions\Models\Permission;
use Mshm\User\Models\User;
use Tests\TestCase;

class LessonTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    // ===================================**************** testing ****************===================================
    public function test_user_can_see_create_lesson_form(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->get(route('lessons.create', $course->id))->assertOk();
        $this->actAsUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course = $this->createCourse();
        $this->get(route('lessons.create', $course->id))->assertOk();
    }

    public function test_normal_user_can_not_see_create_lesson_form(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->actAsUser();
        $this->get(route('lessons.create', $course->id))->assertStatus(403);
    }

    public function test_permitted_user_can_store_lesson(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->post(route('lessons.store', $course->id), [
            "title" => "lesson one",
            "time" => "20",
            "is_free" => 1,
            "lesson_file" => UploadedFile::fake()->create('fake12345.mp4', 10240),
        ]);
        self::assertEquals(1, Lesson::query()->count());
    }

    public function test_only_allowed_extensions_can_be_uploaded(): void
    {
        $notAllowedExtensions = ['jpg', 'png', 'mp3'];
        $this->actAsAdmin();
        $course = $this->createCourse();
        foreach ($notAllowedExtensions as $notAllowedExtension) {
            $this->post(route('lessons.store', $course->id), [
                "title" => "lesson one",
                "time" => "20",
                "is_free" => 1,
                "lesson_file" => UploadedFile::fake()->create('fake12345' . $notAllowedExtension, 10240),
            ]);
        }
        self::assertEquals(0, Lesson::query()->count());
    }

    public function test_permitted_user_can_edit_lesson(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $this->get(route('lessons.edit', [$lesson->id, $course->id]))->assertOk();
        $this->actAsUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $this->get(route('lessons.edit', [$lesson->id, $course->id]))->assertOk();
        $this->actAsUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->get(route('lessons.edit', [$lesson->id, $course->id]))->assertStatus(403);
    }

    public function test_permitted_user_can_update_user(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $this->patch(route('lessons.update', [$course->id, $lesson->id]), [
            'title' => 'updated title1',
            'time' => '21',
            'is_free' => 0
        ]);
        self::assertEquals('updated title1', $lesson->find(1)->title);
        $this->actAsUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.update', [$course->id, $lesson->id]), [
            'title' => 'updated title2',
            'time' => '22',
            'is_free' => 1
        ])->assertStatus(403);
        self::assertEquals('updated title1', $lesson->find(1)->title);
    }

    public function test_permitted_user_can_accept_lesson(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(1)->confirmation_status);
        $this->patch(route('lessons.accept', $lesson->id));
        self::assertEquals(Lesson::CONFIRMATION_STATUS_ACCEPTED, Lesson::find(1)->confirmation_status);
        $this->actAsUser();
        $this->patch(route('lessons.accept', $lesson->id))->assertStatus(403);
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.accept', $lesson->id))->assertStatus(403);
    }

    public function test_permitted_user_can_accept_all_lesson(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $course2 = $this->createCourse();
        $course3 = $this->createCourse();
        /** @noinspection PhpUnusedLocalVariableInspection */
        $lesson = $this->createLesson($course);
        /** @noinspection PhpUnusedLocalVariableInspection */
        $lesson2 = $this->createLesson($course2);
        /** @noinspection PhpUnusedLocalVariableInspection */
        $lesson3 = $this->createLesson($course3);
        /** @noinspection PhpUnusedLocalVariableInspection */
        $lesson4 = $this->createLesson($course3);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(1)->confirmation_status);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(2)->confirmation_status);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(4)->confirmation_status);
        $this->patch(route('lessons.acceptAll', $course->id));
        self::assertEquals($course->lessons()->count(), $course->lessons()
            ->where('confirmation_status', Lesson::CONFIRMATION_STATUS_ACCEPTED)->count());
        self::assertEquals($course2->lessons()->count(), $course2->lessons()
            ->where('confirmation_status', Lesson::CONFIRMATION_STATUS_PENDING)->count());
        self::assertEquals($course3->lessons()->count(), $course3->lessons()
            ->where('confirmation_status', Lesson::CONFIRMATION_STATUS_PENDING)->count());
        $this->actAsUser();
        $this->patch(route('lessons.acceptAll', $course2->id))->assertStatus(403);
        self::assertEquals($course2->lessons()->count(), $course2->lessons()
            ->where('confirmation_status', Lesson::CONFIRMATION_STATUS_PENDING)->count());
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.acceptAll', $course2->id))->assertStatus(403);
        self::assertEquals($course2->lessons()->count(), $course2->lessons()
            ->where('confirmation_status', Lesson::CONFIRMATION_STATUS_PENDING)->count());
    }

    public function test_permitted_user_can_accept_multiple_lessons(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->createLesson($course);
        $this->patch(route('lessons.acceptMultiple', $course->id), [
            "ids" => '1,2'
        ]);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_ACCEPTED, Lesson::find(1)->confirmation_status);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_ACCEPTED, Lesson::find(2)->confirmation_status);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);
        $this->actAsUser();
        $this->patch(route('lessons.acceptMultiple', $course->id), [
            "ids" => '3'
        ])->assertStatus(403);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.acceptMultiple', $course->id), [
            "ids" => '3'
        ])->assertStatus(403);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);
    }

    public function test_permitted_user_can_reject_lesson(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(1)->confirmation_status);
        $this->patch(route('lessons.reject', $lesson->id));
        self::assertEquals(Lesson::CONFIRMATION_STATUS_REJECTED, Lesson::find(1)->confirmation_status);
        $this->actAsUser();
        $this->patch(route('lessons.reject', $lesson->id))->assertStatus(403);
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.reject', $lesson->id))->assertStatus(403);
    }

    public function test_permitted_user_can_reject_multiple_lessons(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->createLesson($course);
        $this->patch(route('lessons.rejectMultiple', $course->id), [
            "ids" => '1,2'
        ]);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_REJECTED, Lesson::find(1)->confirmation_status);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_REJECTED, Lesson::find(2)->confirmation_status);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);
        $this->actAsUser();
        $this->patch(route('lessons.rejectMultiple', $course->id), [
            "ids" => '3'
        ])->assertStatus(403);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->patch(route('lessons.rejectMultiple', $course->id), [
            "ids" => '3'
        ])->assertStatus(403);
        self::assertEquals(Lesson::CONFIRMATION_STATUS_PENDING, Lesson::find(3)->confirmation_status);
    }

    public function test_permitted_user_can_lock_lesson(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->patch(route('lessons.lock', 1));
        self::assertEquals(Lesson::STATUS_LOCKED, Lesson::find(1)->status);
        self::assertEquals(Lesson::STATUS_OPENED, Lesson::find(2)->status);
        $this->actAsUser();
        $this->patch(route('lessons.lock', 2))->assertStatus(403);
        self::assertEquals(Lesson::STATUS_OPENED, Lesson::find(2)->status);
    }

    public function test_permitted_user_can_unlock_lesson(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->patch(route('lessons.lock', 1));
        $this->patch(route('lessons.lock', 2));
        self::assertEquals(Lesson::STATUS_LOCKED, Lesson::find(1)->status);
        $this->patch(route('lessons.unlock', 1));
        self::assertEquals(Lesson::STATUS_OPENED, Lesson::find(1)->status);
        self::assertEquals(Lesson::STATUS_LOCKED, Lesson::find(2)->status);
        $this->actAsUser();
        $this->patch(route('lessons.unlock', 2))->assertStatus(403);
        self::assertEquals(Lesson::STATUS_LOCKED, Lesson::find(2)->status);
    }

    public function test_permitted_user_can_destroy_lesson(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $lesson = $this->createLesson($course);
        $lesson2 = $this->createLesson($course);
        $this->delete(route('lessons.destroy', [$course->id, $lesson->id]))->assertStatus(200);
        self::assertEquals(0, Lesson::find(1));
        $this->actAsUser();
        $this->delete(route('lessons.destroy', [$course->id, $lesson2->id]))->assertStatus(403);
        self::assertEquals(1, Lesson::where('id',2)->count());
    }

    public function test_permitted_user_can_destroy_multiple_lessons(): void
    {
        $this->actAsAdmin();
        $course = $this->createCourse();
        $this->createLesson($course);
        $this->createLesson($course);
        $this->createLesson($course);
        $this->delete(route('lessons.destroyMultiple', $course->id), [
            "ids" => '1,2'
        ]);
        self::assertEquals(0, Lesson::find(1));
        self::assertEquals(0, Lesson::find(2));
        self::assertEquals(3, Lesson::find(3)->id);
        $this->actAsUser();
        $this->delete(route('lessons.destroyMultiple', $course->id), [
            "ids" => '3'
        ])->assertStatus(403);
        self::assertEquals(3, Lesson::find(3)->id);
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
        $this->delete(route('lessons.destroyMultiple', $course->id), [
            "ids" => '3'
        ])->assertStatus(403);
        self::assertEquals(3, Lesson::find(3)->id);
    }

    // ====================================**************** utils ****************====================================
    // ================================ for create fake normal user ================================
    private function actAsUser(): void
    {
        $this->createUser();
    }

    // ================================ for create fake admin ================================
    private function actAsAdmin(): void
    {
        $this->createUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_MANAGE_COURSES);
    }

    // ================================ for create fake admin ================================

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function actAsSuperAdmin(): void
    {
        $this->createUser();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->givePermissionTo(Permission::PERMISSION_SUPER_ADMIN);
    }

    // ================================ for create fake user ================================
    private function createUser(): void
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

    // ================================ for create fake course ================================
    private function createLesson($course)
    {
        return Lesson::create([
            'title' => 'lesson one',
            'slug' => 'lesson one',
            'course_id' => $course->id,
            'user_id' => auth()->id(),
        ]);
    }

    // ================================ create course fake for test ================================
    private function courseData(): array
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
