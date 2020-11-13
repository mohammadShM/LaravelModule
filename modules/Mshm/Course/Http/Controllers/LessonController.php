<?php

namespace Mshm\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\Common\Responses\AjaxResponses;
use Mshm\Course\Http\Requests\LessonRequest;
use Mshm\Course\Models\Lesson;
use Mshm\Course\Repositories\CourseRepo;
use Mshm\Course\Repositories\LessonRepo;
use Mshm\Course\Repositories\SeasonRepo;
use Mshm\Media\Services\MediaFileService;
use function Mshm\Common\newFeedback;
use Illuminate\Http\Request;

class LessonController extends Controller
{

    private $lessonRepo;

    public function __construct(LessonRepo $lessonRepo)
    {
        $this->lessonRepo = $lessonRepo;
    }

    public function create($course, SeasonRepo $seasonRepo, CourseRepo $courseRepo)
    {
        $seasons = $seasonRepo->getCourseSeason($course);
        $course = $courseRepo->findById($course);
        return view('Courses::lessons.create', compact('seasons', 'course'));
    }

    public function store($course, LessonRequest $request)
    {
        $request->request->add(['media_id' => MediaFileService::privateUpload($request
            ->file('lesson_file'))->id]);
        $this->lessonRepo->store($course, $request);
        newFeedback();
        return redirect(route('courses.details', $course));
    }

    public function accept($id)
    {
        $this->lessonRepo->updateConfirmationStatus($id, Lesson::CONFIRMATION_STATUS_ACCEPTED);
        return AjaxResponses::successResponse();
    }

    public function reject($id)
    {
        $this->lessonRepo->updateConfirmationStatus($id, Lesson::CONFIRMATION_STATUS_REJECTED);
        return AjaxResponses::successResponse();
    }

    public function lock($id)
    {
        if ($this->lessonRepo->updateStatus($id, Lesson::STATUS_LOCKED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function unlock($id)
    {
        if ($this->lessonRepo->updateStatus($id, Lesson::STATUS_OPENED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function destroy($courseId, $lessonId)
    {
        $lesson = $this->lessonRepo->findById($lessonId);
        if ($lesson->media) {
            $lesson->media->delete();
        }
        $lesson->delete();
        return AjaxResponses::successResponse();
    }

    public function destroyMultiple(Request $request)
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $ids = explode(',', $request->ids);
        foreach ($ids as $id) {
            $lesson = $this->lessonRepo->findById($id);
            if ($lesson->media) {
                $lesson->media->delete();
            }
            $lesson->delete();
        }
        newFeedback();
        return back();
    }

}
