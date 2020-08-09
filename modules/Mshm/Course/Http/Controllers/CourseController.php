<?php

namespace Mshm\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\User\Repositories\UserRepo;

class CourseController extends Controller
{

    public function index()
    {
        return 'courses';
    }

    public function create(UserRepo $userRepo)
    {
        $teachers = $userRepo->getTeachers();
        return view('Courses::create',compact('teachers'));
    }

}
