<?php

namespace Mshm\Payment\Contracts;

use Mshm\Payment\Models\Payment;

interface GateWayContract
{

    public function request($amount, $description);

    public function verify(Payment $payment);

    public function redirect();

    public function getName();

}
