<?php

namespace Mshm\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\Course\Repositories\SeasonRepo;

class LessonController extends Controller
{

    public function create($course, SeasonRepo  $seasonRepo)
    {
        $seasons = $seasonRepo->getCourseSeason($course);
       return view('Courses::lessons.create',compact('seasons'));
    }

}
