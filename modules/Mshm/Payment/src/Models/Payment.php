<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace Mshm\Payment\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Payment extends Model
{

    protected $guarded = [];

    public const STATUS_PENDING = 'pending';
    public const STATUS_Canceled = 'canceled';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL = 'fail';
    public static array $statuses = [
        self::STATUS_PENDING,
        self::STATUS_Canceled,
        self::STATUS_SUCCESS,
        self::STATUS_FAIL,
    ];
}
