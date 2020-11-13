<?php

namespace Mshm\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Mshm\Media\Models\Media;
use Mshm\User\Models\User;

/**
 * @method static create(array $array)
 * @method static paginate()
 * @method static findOrFail($id)
 * @method static where(string $string, $id)
 * @method static orderBy(string $string)
 * @property mixed confirmation_status
 */
class Lesson extends Model
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

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function getConfirmationStatusCssClass()
    {
        if ($this->confirmation_status == self::CONFIRMATION_STATUS_ACCEPTED) return "text-success";
        elseif ($this->confirmation_status == self::CONFIRMATION_STATUS_REJECTED) return "text-error";
        else return "#919191";
    }

}
