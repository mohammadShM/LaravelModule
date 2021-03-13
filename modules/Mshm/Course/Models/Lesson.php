<?php

namespace Mshm\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mshm\Media\Models\Media;
use Mshm\User\Models\User;

/**
 * @method static create(array $array)
 * @method static paginate()
 * @method static findOrFail($id)
 * @method static where(string $string, $id)
 * @method static orderBy(string $string)
 * @method static find(int $int)
 * @property mixed confirmation_status
 */
class Lesson extends Model
{

    protected $guarded = [];

    public const CONFIRMATION_STATUS_ACCEPTED = 'accepted';
    public const CONFIRMATION_STATUS_REJECTED = 'rejected';
    public const CONFIRMATION_STATUS_PENDING = 'pending';
    public static array $confirmationStatuses = [self::CONFIRMATION_STATUS_ACCEPTED,
        self::CONFIRMATION_STATUS_REJECTED
        , self::CONFIRMATION_STATUS_PENDING];

    public const STATUS_OPENED = 'opened';
    public const STATUS_LOCKED = 'locked';
    public static array $statuses = [self::STATUS_OPENED, self::STATUS_LOCKED];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /** @noinspection TypeUnsafeComparisonInspection */
    public function getConfirmationStatusCssClass(): string
    {
        if ($this->confirmation_status == self::CONFIRMATION_STATUS_ACCEPTED) {
            return "text-success";
        }

        if ($this->confirmation_status == self::CONFIRMATION_STATUS_REJECTED) {
            return "text-error";
        }

        return "#919191";
    }

}
