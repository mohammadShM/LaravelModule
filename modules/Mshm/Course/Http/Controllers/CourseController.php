<?php

namespace Mshm\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\Category\Repositories\CategoryRepo;
use Mshm\Course\Http\Requests\CourseRequest;
use Mshm\Course\Models\Course;
use Mshm\Course\Repositories\CourseRepo;
use Mshm\User\Repositories\UserRepo;

class CourseController extends Controller
{

    public function index()
    {
        return 'courses';
    }

    public function create(UserRepo $userRepo, CategoryRepo $categoryRepo)
    {
        $teachers = $userRepo->getTeachers();
        $categories = $categoryRepo->all();
        return view('Courses::create', compact('teachers', 'categories'));
    }

    public function store(CourseRequest $request, CourseRepo $courseRepo)
    {
        /** @var Course $course */
        $course = $courseRepo->store($request);
        return $course;
    }

}
