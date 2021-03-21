<?php /** @noinspection PhpMissingReturnTypeInspection */

/** @noinspection ReturnTypeCanBeDeclaredInspection */

namespace Mshm\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mshm\Payment\Events\PaymentWasSuccessful;
use Mshm\Payment\Gateways\Gateway;
use Mshm\Payment\Models\Payment;
use Mshm\Payment\Repositories\PaymentRepo;
use function Mshm\Common\newFeedback;

class PaymentController extends Controller
{

    public function callback(Request $request)
    {
        $gateway = resolve(Gateway::class);
        $paymentRepo = new PaymentRepo();
        $payment = $paymentRepo->findByInvoiceId($gateway->getInvoiceIdFromRequest($request));
        if (!$payment) {
            newFeedback("تراکنش ناموفق", "تراکنش مورد نظر یافت نشد", "error");
            return redirect("/");
        }
        $result = $gateway->verify($payment);
        if (is_array($result)) {
            newFeedback("عملیات ناموفق", $result['message'], "error");
            $paymentRepo->changeStatus($payment->id, Payment::STATUS_FAIL);
            // todo: later complete Failed
        } else {
            // todo: later complete Success
            event(new PaymentWasSuccessful($payment));
            /** @noinspection PhpRedundantOptionalArgumentInspection */
            newFeedback("عملیات موفق", "پرداخت با موفقیت انجام شد", "success");
            $paymentRepo->changeStatus($payment->id, Payment::STATUS_SUCCESS);
        }
        return redirect()->to($payment->paymentable->path());
    }

}
