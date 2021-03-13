<?php

namespace Mshm\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\Common\Responses\AjaxResponses;
use Mshm\Course\Http\Requests\LessonRequest;
use Mshm\Course\Models\Course;
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
        $course = $courseRepo->findById($course);
        $this->authorize('createLesson', $course);
        $seasons = $seasonRepo->getCourseSeason($course->id);
        return view('Courses::lessons.create', compact('seasons', 'course'));
    }

    public function store($course, LessonRequest $request, CourseRepo $courseRepo)
    {
        $course = $courseRepo->findById($course);
        $this->authorize('createLesson', $course);
        $request->request->add(['media_id' => MediaFileService::privateUpload($request
            ->file('lesson_file'))->id]);
        $this->lessonRepo->store($course->id, $request);
        newFeedback();
        return redirect(route('courses.details', $course->id));
    }

    public function edit($courseId, $lessonId, SeasonRepo $seasonRepo, CourseRepo $courseRepo)
    {
        $lesson = $this->lessonRepo->findById($lessonId);
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('edit', $lesson);
        $seasons = $seasonRepo->getCourseSeason($courseId);
        $course = $courseRepo->findById($courseId);
        return view('Courses::lessons.edit', compact('lesson', 'seasons', 'course'));
    }

    public function update($courseId, $lessonId, LessonRequest $request)
    {
        $lesson = $this->lessonRepo->findById($lessonId);
        $this->authorize('edit', $lesson);
        if ($request->hasFile('lesson_file')) {
            if ($lesson->media) $lesson->media->delete();
            $request->request->add(['media_id' => MediaFileService::privateUpload($request
                ->file('lesson_file'))->id]);
        } else {
            $request->request->add(['media_id' => $lesson->media_id]);
        }
        $this->lessonRepo->update($lessonId, $courseId, $request);
        newFeedback();
        return redirect(route('courses.details', $courseId));
    }

    public function accept($id)
    {
        $this->authorize('manage', Course::class);
        $this->lessonRepo->updateConfirmationStatus($id, Lesson::CONFIRMATION_STATUS_ACCEPTED);
        return AjaxResponses::successResponse();
    }

    public function acceptAll($courseId)
    {
        $this->authorize('manage', Course::class);
        $this->lessonRepo->acceptAll($courseId);
        newFeedback();
        return back();
    }

    public function acceptMultiple(Request $request)
    {
        $this->authorize('manage', Course::class);
        /** @noinspection PhpUndefinedFieldInspection */
        $ids = explode(',', $request->ids);
        $this->lessonRepo->updateConfirmationStatus($ids, Lesson::CONFIRMATION_STATUS_ACCEPTED);
        newFeedback();
        return back();
    }

    public function rejectMultiple(Request $request)
    {
        $this->authorize('manage', Course::class);
        /** @noinspection PhpUndefinedFieldInspection */
        $ids = explode(',', $request->ids);
        $this->lessonRepo->updateConfirmationStatus($ids, Lesson::CONFIRMATION_STATUS_REJECTED);
        newFeedback();
        return back();
    }

    public function reject($id)
    {
        $this->authorize('manage', Course::class);
        $this->lessonRepo->updateConfirmationStatus($id, Lesson::CONFIRMATION_STATUS_REJECTED);
        return AjaxResponses::successResponse();
    }

    public function lock($id)
    {
        $this->authorize('manage', Course::class);
        if ($this->lessonRepo->updateStatus($id, Lesson::STATUS_LOCKED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function unlock($id)
    {
        $this->authorize('manage', Course::class);
        if ($this->lessonRepo->updateStatus($id, Lesson::STATUS_OPENED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function destroy($courseId, $lessonId)
    {
        $lesson = $this->lessonRepo->findById($lessonId);
        $this->authorize('delete', $lesson);
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
            $this->authorize('delete', $lesson);
            if ($lesson->media) {
                $lesson->media->delete();
            }
            $lesson->delete();
        }
        newFeedback();
        return back();
    }

}
