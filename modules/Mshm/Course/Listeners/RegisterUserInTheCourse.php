<?php

namespace Mshm\Course\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mshm\Course\Models\Course;
use Mshm\Course\Repositories\CourseRepo;

class RegisterUserInTheCourse
{

    public function __construct()
    {

    }

    public function handle($event)
    {
        if ($event->payment->paymentable_type == Course::class){
            resolve(CourseRepo::class)
                ->addStudentToCourse($event->payment->paymentable,$event->payment->buyer_id);
        }
    }
}
