<?php /** @noinspection PhpMissingReturnTypeInspection */

/** @noinspection ReturnTypeCanBeDeclaredInspection */

namespace Mshm\Front\Http\Controllers;

use Illuminate\Support\Str;
use Mshm\Course\Repositories\CourseRepo;
use Mshm\Course\Repositories\LessonRepo;
use Mshm\RolePermissions\Models\Permission;
use Mshm\User\Models\User;

class FrontController
{

    public function index()
    {
        return view('Front::index');
    }

    /** @noinspection PhpUndefinedFieldInspection */
    public function singleCourse($slug, CourseRepo $courseRepo, LessonRepo $lessonRepo)
    {
        $courseId = $this->extractId($slug, 'c');
        $course = $courseRepo->findById($courseId);
        $lessons = $lessonRepo->getAcceptedLessons($courseId);
        if (request()->lesson) {
            $lesson = $lessonRepo->getLesson($courseId, $this->extractId(request()->lesson, 'l'));
        } else {
            $lesson = $lessonRepo->getFirstLesson($courseId);
        }
        return view('Front::singleCourse', compact('course', 'lessons', 'lesson'));
    }

    public function extractId($slug, $key)
    {
        return Str::before(Str::after($slug, $key . '-'), '-');
    }

    public function singleTutor($username)
    {
        $tutor = User::permission(Permission::PERMISSION_TEACH)
            ->where('username', $username)->first();
        return view('Front::tutor', compact('tutor'));
    }

}
