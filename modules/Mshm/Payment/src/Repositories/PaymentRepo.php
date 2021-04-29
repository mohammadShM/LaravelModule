<?php /** @noinspection PhpMissingReturnTypeInspection */

/** @noinspection ReturnTypeCanBeDeclaredInspection */

namespace Mshm\Payment\Repositories;

use Mshm\Payment\Models\Payment;

class PaymentRepo
{

    public function store($data)
    {
        return Payment::create([
            'buyer_id' => $data['buyer_id'],
            'paymentable_id' => $data['paymentable_id'],
            'paymentable_type' => $data['paymentable_type'],
            'amount' => $data['amount'],
            'invoice_id' => $data['invoice_id'],
            'gateway' => $data['gateway'],
            'status' => $data['status'],
            'seller_p' => $data['seller_p'],
            'seller_share' => $data['seller_share'],
            'site_share' => $data['site_share'],
        ]);
    }

    public function findByInvoiceId($invoiceId)
    {
        return Payment::where('invoice_id', $invoiceId)->first();
    }

    public function changeStatus($id, $status)
    {
        return Payment::where("id", $id)->update([
            "status" => $status
        ]);
    }

    public function paginate()
    {
        return Payment::query()->latest()->paginate();
    }

    public function getLastNDaysPayments($status, $days = null)
    {
        $query = Payment::query();
        if (!is_null($days)) {
            $query = $query->where("created_at", ">=", now()->addDays($days));
        }
        return $query->where("status", $status)->latest();
    }

    public function getLastNDaysSuccessPayments($days = null)
    {
        return $this->getLastNDaysPayments(Payment::STATUS_SUCCESS, $days);
    }

    public function getLastNDaysTotal($days = null)
    {
        return $this->getLastNDaysSuccessPayments($days)->sum("amount");
    }

    public function getLastNDaysSiteBenefit($days = null)
    {
        return $this->getLastNDaysSuccessPayments($days)->sum("site_share");
    }

    public function getLastNDaysSellerBenefit($days = null)
    {
        return $this->getLastNDaysSuccessPayments($days)->sum("seller_share");
    }

    public function getDayPayment($day, $status)
    {
        return Payment::query()->whereDate("created_at", $day)
            ->where("status", $status)->latest();
    }

    public function getDaySuccessPayment($day)
    {
        return $this->getDayPayment($day, Payment::STATUS_SUCCESS);
    }

    public function getDayFailedPayment($day)
    {
        return $this->getDayPayment($day, Payment::STATUS_FAIL);
    }

    public function getDaySuccessPaymentTotal($day)
    {
        return $this->getDaySuccessPayment($day)->sum("amount");
    }

    public function getDayFailedPaymentTotal($day)
    {
        return $this->getDayFailedPayment($day)->sum("amount");
    }

    public function getDaySiteShare($day)
    {
        return $this->getDaySuccessPayment($day)->sum("site_share");
    }

    public function getDaySellerShare($day)
    {
        return $this->getDaySuccessPayment($day)->sum("seller_share");
    }

}
