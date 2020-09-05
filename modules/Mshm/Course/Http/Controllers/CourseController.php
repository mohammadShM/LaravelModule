<?php

namespace Mshm\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\Category\Repositories\CategoryRepo;
use Mshm\Common\Responses\AjaxResponses;
use Mshm\Course\Http\Requests\CourseRequest;
use Mshm\Course\Models\Course;
use Mshm\Course\Repositories\CourseRepo;
use Mshm\Media\Services\MediaFileService;
use Mshm\User\Repositories\UserRepo;

class CourseController extends Controller
{

    public function index(CourseRepo $courseRepo)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('manage', Course::class);
        $courses = $courseRepo->paginate();
        return view('Courses::index', compact('courses'));
    }

    public function create(UserRepo $userRepo, CategoryRepo $categoryRepo)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('create', Course::class);
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
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('edit', $course);
        $teachers = $userRepo->getTeachers();
        $categories = $categoryRepo->all();
        return view('Courses::edit', compact('course', 'teachers', 'categories'));
    }

    public function update($id, CourseRequest $request, CourseRepo $courseRepo)
    {
        $course = $courseRepo->findById($id);
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('edit', $course);
        if ($request->hasFile('image')) {
            $request->request->add(['banner_id' => MediaFileService::upload($request->file('image'))->id]);
            if ($course->banner) {
                $course->banner->delete();
            }
        } else {
            $request->request->add(['banner_id' => $course->banner_id]);
        }
        $courseRepo->update($id, $request);
        return redirect(route('courses.index'));
    }

    public function destroy($id, CourseRepo $courseRepo)
    {
        // DELETE MEDIA (BANNER)
        $course = $courseRepo->findById($id);
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('delete', $course);
        if ($course->banner) {
            $course->banner->delete();
        }
        // DELETE LESSONS (NOTHING)
        // DELETE PAYMENT (NOTHING)
        // DELETE COURSE
        $course->delete();
        return AjaxResponses::successResponse();
    }

    public function accept($id, CourseRepo $courseRepo)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('change_confirmation_status', Course::class);
        if ($courseRepo->updateConfirmationStatus($id, Course::CONFIRMATION_STATUS_ACCEPTED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function reject($id, CourseRepo $courseRepo)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('change_confirmation_status', Course::class);
        if ($courseRepo->updateConfirmationStatus($id, Course::CONFIRMATION_STATUS_REJECTED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function lock($id, CourseRepo $courseRepo)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('change_confirmation_status', Course::class);
        if ($courseRepo->updateStatus($id, Course::STATUS_LOCKED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

}