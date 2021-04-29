<?php
/** @noinspection ReturnTypeCanBeDeclaredInspection */

/** @noinspection PhpMissingReturnTypeInspection */

namespace Mshm\Payment\Services;

use Mshm\Payment\Gateways\Gateway;
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
        $gateway = resolve(Gateway::class);
        $invoiceId = $gateway->request($amount, $paymentable->title);
        if (is_array($invoiceId)) {
            //todo: later complete
            return back();
        }
        if (!is_null($paymentable->percent)) {
            $seller_p = $paymentable->percent;
            $seller_share = ($amount / 100) * $seller_p;
            $site_share = $amount - $seller_share;
        } else {
            $seller_p = $seller_share = 0;
            $site_share = $amount;
        }
        return resolve(PaymentRepo::class)->store([
            'buyer_id' => $buyer->id,
            'paymentable_id' => $paymentable->id,
            'paymentable_type' => get_class($paymentable),
            'amount' => $amount,
            'invoice_id' => $invoiceId,
            'gateway' => $gateway->getName(),
            'status' => Payment::STATUS_PENDING,
            'seller_p' => $seller_p,
            'seller_share' => $seller_share,
            'site_share' => $site_share,
        ]);
    }

}
