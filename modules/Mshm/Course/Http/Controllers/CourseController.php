<?php

namespace Mshm\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\Category\Repositories\CategoryRepo;
use Mshm\Category\Responses\AjaxResponses;
use Mshm\Course\Http\Requests\CourseRequest;
use Mshm\Course\Repositories\CourseRepo;
use Mshm\Media\Services\MediaFileService;
use Mshm\User\Repositories\UserRepo;

class CourseController extends Controller
{

    public function index(CourseRepo $courseRepo)
    {
        $courses = $courseRepo->paginate();
        return view('Courses::index', compact('courses'));
    }

    public function create(UserRepo $userRepo, CategoryRepo $categoryRepo)
    {
        $teachers = $userRepo->getTeachers();
        $categories = $categoryRepo->all();
        return view('Courses::create', compact('teachers', 'categories'));
    }

    public function store(CourseRequest $request, CourseRepo $courseRepo)
    {
        $request->request->add(['banner_id' => MediaFileService::upload($request->file('image'))->id]);
        $courseRepo->store($request);
        return redirect()->route('courses.index');
    }

    public function edit($id, CourseRepo $courseRepo, UserRepo $userRepo, CategoryRepo $categoryRepo)
    {
        $course = $courseRepo->findById($id);
        $teachers = $userRepo->getTeachers();
        $categories = $categoryRepo->all();
        return view('Courses::edit', compact('course', 'teachers', 'categories'));
    }

    public function destroy($id, CourseRepo $courseRepo)
    {
        // DELETE MEDIA (BANNER)
        $course = $courseRepo->findById($id);
        if ($course->banner) {
            $course->banner->delete();
        }
        // DELETE LESSONS (NOTHING)
        // DELETE PAYMENT (NOTHING)
        // DELETE COURSE
        $course->delete();
        return AjaxResponses::successResponse();
    }

}
