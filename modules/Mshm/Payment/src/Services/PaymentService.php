<?php
/** @noinspection ReturnTypeCanBeDeclaredInspection */

/** @noinspection PhpMissingReturnTypeInspection */

namespace Mshm\Payment\Services;

use Mshm\Payment\Models\Payment;
use Mshm\Payment\Repositories\PaymentRepo;
use Mshm\User\Models\User;

class PaymentService
{

    public static function generate($amount, $paymentable, User $buyer)
    {
        if ($amount <= 0 || is_null($paymentable->id) || is_null($buyer->id)) {
            return false;
        }
        $gateway = "";
        $invoiceId = 0;
        if (is_null($paymentable->percent)) {
            $seller_p = $paymentable->percent;
            $seller_share = ($amount / 100) * $seller_p;
            $site_share = $amount - $seller_share;
        } else {
            $seller_p = $seller_share = $site_share = 0;
        }
        $data = [
            'buyer_id' => $buyer->id,
            'paymentable_id' => $paymentable->id,
            'paymentable_type' => get_class($paymentable),
            'amount' => $amount,
            'invoice_id' => $invoiceId,
            'gateway' => $gateway,
            'status' => Payment::STATUS_PENDING,
            'seller_p' => $seller_p,
            'seller_share' => $seller_share,
            'site_share' => $site_share,
        ];
        return resolve(PaymentRepo::class)->store($data);
    }

}
