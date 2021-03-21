<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection TypeUnsafeComparisonInspection */
/** @noinspection ReturnTypeCanBeDeclaredInspection */
/** @noinspection PhpMissingReturnTypeInspection */
/** @noinspection NullPointerExceptionInspection */
/** @noinspection PhpUndefinedMethodInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Mshm\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\Category\Repositories\CategoryRepo;
use Mshm\Common\Responses\AjaxResponses;
use Mshm\Course\Http\Requests\CourseRequest;
use Mshm\Course\Models\Course;
use Mshm\Course\Repositories\CourseRepo;
use Mshm\Course\Repositories\LessonRepo;
use Mshm\Media\Services\MediaFileService;
use Mshm\Payment\Gateways\Gateway;
use Mshm\Payment\Services\PaymentService;
use Mshm\RolePermissions\Models\Permission;
use Mshm\User\Repositories\UserRepo;
use function Mshm\Common\newFeedback;

class CourseController extends Controller
{

    public function index(CourseRepo $courseRepo)
    {
        $this->authorize('index', Course::class);
        if (auth()->user()->hasAnyPermission([Permission::PERMISSION_MANAGE_COURSES,
            Permission::PERMISSION_SUPER_ADMIN])) {
            $courses = $courseRepo->paginate();
        } else {
            $courses = $courseRepo->getCoursesByTeacherId(auth()->id());
        }
        return view('Courses::index', compact('courses'));
    }

    public function create(UserRepo $userRepo, CategoryRepo $categoryRepo)
    {
        $this->authorize('create', Course::class);
        $teachers = $userRepo->getTeachers();
        $categories = $categoryRepo->all();
        return view('Courses::create', compact('teachers', 'categories'));
    }

    public function store(CourseRequest $request, CourseRepo $courseRepo)
    {
        $request->request->add(['banner_id' => MediaFileService::publicUpload($request->file('image'))->id]);
        $courseRepo->store($request);
        return redirect()->route('courses.index');
    }

    public function edit($id, CourseRepo $courseRepo, UserRepo $userRepo, CategoryRepo $categoryRepo)
    {
        $course = $courseRepo->findById($id);
        $this->authorize('edit', $course);
        $teachers = $userRepo->getTeachers();
        $categories = $categoryRepo->all();
        return view('Courses::edit', compact('course', 'teachers', 'categories'));
    }

    public function update($id, CourseRequest $request, CourseRepo $courseRepo)
    {
        $course = $courseRepo->findById($id);
        $this->authorize('edit', $course);
        if ($request->hasFile('image')) {
            $request->request->add(['banner_id' => MediaFileService::publicUpload($request
                ->file('image'))->id]);
            if ($course->banner) {
                $course->banner->delete();
            }
        } else {
            $request->request->add(['banner_id' => $course->banner_id]);
        }
        $courseRepo->update($id, $request);
        return redirect(route('courses.index'));
    }

    public function details($id, CourseRepo $courseRepo, LessonRepo $lessonRepo)
    {
        $course = $courseRepo->findById($id);
        $lessons = $lessonRepo->paginate($id);
        $this->authorize('details', $course);
        return view('Courses::details', compact('course', 'lessons'));
    }

    public function destroy($id, CourseRepo $courseRepo)
    {
        // DELETE MEDIA (BANNER)
        $course = $courseRepo->findById($id);
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
        $this->authorize('change_confirmation_status', Course::class);
        if ($courseRepo->updateConfirmationStatus($id, Course::CONFIRMATION_STATUS_ACCEPTED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function reject($id, CourseRepo $courseRepo)
    {
        $this->authorize('change_confirmation_status', Course::class);
        if ($courseRepo->updateConfirmationStatus($id, Course::CONFIRMATION_STATUS_REJECTED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function lock($id, CourseRepo $courseRepo)
    {
        $this->authorize('change_confirmation_status', Course::class);
        if ($courseRepo->updateStatus($id, Course::STATUS_LOCKED)) {
            return AjaxResponses::successResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    /** @noinspection PhpUnused */
    public function buy($courseId, CourseRepo $courseRepo)
    {
        $course = $courseRepo->findById($courseId);
        if (!$this->courseCanPurchased($course)) {
            return back();
        }
        if (!$this->authUserCanPurchasedCourse($course)) {
            return back();
        }
        $amount = $course->getFinalPrice();
        if ($amount <= 0) {
            // for free course by Discount
            $courseRepo->addStudentToCourse($course, auth()->id());
            /** @noinspection PhpRedundantOptionalArgumentInspection */
            newFeedback("عملیات موفقیت آمیز", "شما با موفقیت در دوره ثبت نام کردید.", "success");
            return redirect($course->path());
        }
        /** @noinspection PhpParamsInspection */
        $payment = PaymentService::generate($amount, $course, auth()->user());
        resolve(Gateway::class)->redirect();
    }

    public function courseCanPurchased(Course $course)
    {
        if ($course->type == Course::TYPE_FREE) {
            newFeedback('عملیات نا موفق', 'دوره های رایگان قابل خریداری نیستند!', 'error');
            return false;
        }
        if ($course->status == Course::STATUS_LOCKED) {
            newFeedback('عملیات نا موفق', 'این دوره قفل شده است و فعلا قابل خریداری نیست!', 'error');
            return false;
        }
        if ($course->confirmation_status != Course::CONFIRMATION_STATUS_ACCEPTED) {
            newFeedback('عملیات نا موفق', 'دوره انتخابی شما هنوز تایید نشده است!', 'error');
            return false;
        }
        return true;
    }

    public function authUserCanPurchasedCourse(Course $course)
    {
        if (auth()->id() == $course->teacher_id) {
            newFeedback('عملیات نا موفق', 'شما مدرس این دوره هستید!', 'error');
            return false;
        }
        if (auth()->user()->can("download", $course)) {
            newFeedback('عملیات نا موفق', 'ما به دوره دسترسی دارید!', 'error');
            return false;
        }
        return true;
    }


}
