<?php

namespace Mshm\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\Common\Responses\AjaxResponses;
use Mshm\Course\Http\Requests\SeasonRequest;
use Mshm\Course\Models\Season;
use Mshm\Course\Repositories\CourseRepo;
use Mshm\Course\Repositories\SeasonRepo;
use function Mshm\Common\newFeedback;

class SeasonController extends Controller
{

    private $seasonRepo;

    public function __construct(SeasonRepo $seasonRepo)
    {
        $this->seasonRepo = $seasonRepo;
    }

    public function store($course, SeasonRequest $request, CourseRepo $courseRepo)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('createSeason', $courseRepo->findById($course));
        $this->seasonRepo->store($course, $request);
        newFeedback();
        return back();
    }

    public function edit($id)
    {
        $season = $this->seasonRepo->findById($id);
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('edit', $season);
        return view('Courses::seasons.edit', compact('season'));
    }

    public function update($id, SeasonRequest $request)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('edit', $this->seasonRepo->findById($id));
        $this->seasonRepo->update($id, $request);
        newFeedback();
        return back();
    }

    public function accept($id)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('change_confirmation_status', Season::class);
        if ($this->seasonRepo->updateConfirmationStatus($id, Season::CONFIRMATION_STATUS_ACCEPTED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function reject($id)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('change_confirmation_status', Season::class);
        if ($this->seasonRepo->updateConfirmationStatus($id, Season::CONFIRMATION_STATUS_REJECTED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function lock($id)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('change_confirmation_status', Season::class);
        if ($this->seasonRepo->updateStatus($id, Season::STATUS_LOCKED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function unlock($id)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('change_confirmation_status', Season::class);
        if ($this->seasonRepo->updateStatus($id, Season::STATUS_OPENED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function destroy($id)
    {
        $season = $this->seasonRepo->findById($id);
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('delete', $season);
        $season->delete();
        return AjaxResponses::successResponse();
    }

}
