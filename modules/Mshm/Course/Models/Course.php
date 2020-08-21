<?php

namespace Mshm\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Mshm\Media\Models\Media;
use Mshm\User\Models\User;

/**
 * @method static create(array $array)
 * @method static paginate()
 * @method static find($id)
 * @method static findOrFail($id)
 * @method static where(string $string, $id)
 * @method static count()
 * @property mixed banner
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

}
