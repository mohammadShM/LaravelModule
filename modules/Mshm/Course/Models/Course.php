<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */
/** @noinspection PhpMissingReturnTypeInspection */
/** @noinspection PhpMissingFieldTypeInspection */

/** @noinspection AccessModifierPresentedInspection */

namespace Mshm\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Mshm\Category\Models\Category;
use Mshm\Course\Repositories\CourseRepo;
use Mshm\Media\Models\Media;
use Mshm\Payment\Models\Payment;
use Mshm\User\Models\User;

/**
 * @method static create(array $array)
 * @method static paginate()
 * @method static find($id)
 * @method static findOrFail($id)
 * @method static where(string $string, $id)
 * @method static count()
 * @property mixed banner
 * @property mixed id
 * @property mixed slug
 * @property mixed price
 * @property mixed teacher_id
 * @property mixed students
 * @property mixed type
 * @property mixed status
 * @property mixed confirmation_status
 */
class Course extends Model
{

    protected $guarded = [];

    const TYPE_FREE = 'free';
    const TYPE_CASH = 'cash';
    static $types = [self::TYPE_FREE, self::TYPE_CASH];

    const STATUS_COMPLETED = 'completed';
    const STATUS_NOT_COMPLETED = 'not-completed';
    const STATUS_LOCKED = 'locked';
    static $statuses = [self::STATUS_COMPLETED, self::STATUS_NOT_COMPLETED, self::STATUS_LOCKED];

    const CONFIRMATION_STATUS_ACCEPTED = 'accepted';
    const CONFIRMATION_STATUS_REJECTED = 'rejected';
    const CONFIRMATION_STATUS_PENDING = 'pending';
    static $confirmationStatuses = [self::CONFIRMATION_STATUS_ACCEPTED, self::CONFIRMATION_STATUS_REJECTED
        , self::CONFIRMATION_STATUS_PENDING];

    public function banner()
    {
        return $this->belongsTo(Media::class, 'banner_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user',
            'course_id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function getDuration()
    {
        return (new CourseRepo())->getDuration($this->id);
    }

    public function hasStudent($student_id)
    {
        return resolve(CourseRepo::class)->hasStudent($this, $student_id);
    }

    public function formattedDuration(): string
    {
        $duration = $this->getDuration();
        $h = round($duration / 60) < 10 ? '0' . round($duration / 60) : round($duration / 60);
        $m = ($duration % 60) < 10 ? '0' . ($duration % 60) : ($duration % 60);
        return $h . ':' . $m . ':00';
    }

    public function getFormattedPrice()
    {
        return number_format($this->price) . ' تومان';
    }

    public function getDiscountPercent()
    {
        //todo: later complete
        return 0;
    }

    public function getDiscountAmount()
    {
        //todo: later complete
        return 0;
    }

    public function getFinalPrice()
    {
        return $this->price - $this->getDiscountAmount();
    }

    public function getFormattedFinalPrice()
    {
        return number_format($this->getFinalPrice());
    }

    public function getFormattedPriceForSingleCourse()
    {
        return number_format($this->price);
    }

    public function path()
    {
        return route('singleCourse', $this->id . '-' . $this->slug);
    }

    public function lessonsCount()
    {
        return (new CourseRepo())->getLessonsCount($this->id);
    }

    public function shortUrl()
    {
        return route('singleCourse', $this->id);
    }

}
