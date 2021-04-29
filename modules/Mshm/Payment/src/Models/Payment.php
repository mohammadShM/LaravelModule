<?php /** @noinspection PhpMissingReturnTypeInspection */

/** @noinspection PhpMissingFieldTypeInspection */

namespace Mshm\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Mshm\User\Models\User;

/**
 * @method static create(array $array)
 * @method static where(string $string, $id)
 * @property mixed amount
 * @property mixed paymentable
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

    public function paymentable()
    {
        return $this->morphTo("paymentable");
    }

    public function buyer()
    {
        return $this->belongsTo(User::class,"buyer_id");
    }

}
