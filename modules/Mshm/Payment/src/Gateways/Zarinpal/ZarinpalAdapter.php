<?php /** @noinspection PhpMissingFieldTypeInspection */
/** @noinspection PhpMissingReturnTypeInspection */
/** @noinspection RedundantElseClauseInspection */
/** @noinspection TypeUnsafeComparisonInspection */

/** @noinspection ReturnTypeCanBeDeclaredInspection */

namespace Mshm\Payment\Gateways\Zarinpal;

use Illuminate\Http\Request;
use Mshm\Payment\Contracts\GateWayContract;
use Mshm\Payment\Models\Payment;


class ZarinpalAdapter implements GateWayContract
{

    private $url;
    private $client;

    public function request($amount, $description)
    {
        $this->client = new ZarinPal();
        $callback = route("payments.callback");
        $MerchantID = "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx";
        $result = $this->client->request($MerchantID, $amount, $description, "", "",
            $callback, true);
        if (isset($result["Status"]) && $result["Status"] == 100) {
            $this->url = $result['StartPay'];
            return $result['Authority'];
        } else {
            return [
                "status" => $result["Status"],
                "message" => $result["Message"],
            ];
        }
    }

    public function verify(Payment $payment)
    {
        if (is_null($payment)) {
            return false;
        }
        $this->client = new ZarinPal();
        $MerchantID = "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx";
        $result = $this->client->verify($MerchantID, $payment->amount, true);
        if (isset($result["Status"]) && $result["Status"] == 100) {
            return $result["RefID"];
        } else {
            return [
                "status" => $result["Status"],
                "message" => $result["Message"],
            ];
        }
    }

    public function redirect()
    {
        $this->client->redirect($this->url);
    }

    public function getName()
    {
        return 'zarinpal';
    }

    /** @noinspection PhpUndefinedFieldInspection */
    public function getInvoiceIdFromRequest(Request $request)
    {
        return $request->Authority;
    }

}
