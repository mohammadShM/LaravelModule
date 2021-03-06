<?php

namespace Mshm\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Mshm\User\Models\User;

/**
 * @method static create(array $array)
 * @method static findOrFail($id)
 * @method static where(string $string, $id)
 * @method static paginate()
 * @method static count()
 * @method static find(int $int)
 */
class Season extends Model
{

    protected $guarded = [];

    const CONFIRMATION_STATUS_ACCEPTED = 'accepted';
    const CONFIRMATION_STATUS_REJECTED = 'rejected';
    const CONFIRMATION_STATUS_PENDING = 'pending';
    static $confirmationStatuses = [self::CONFIRMATION_STATUS_ACCEPTED, self::CONFIRMATION_STATUS_REJECTED
        , self::CONFIRMATION_STATUS_PENDING];

    const STATUS_OPENED = 'opened';
    const STATUS_LOCKED = 'locked';
    static $statuses = [self::STATUS_OPENED, self::STATUS_LOCKED];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

}
