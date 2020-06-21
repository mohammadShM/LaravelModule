<?php

namespace Mshm\User\Tests\Unit;

use Mshm\User\Services\VerifyCodeService;
use Tests\TestCase;

class VerifyCodeServiceTest extends TestCase
{

    public function test_generate_code_is_6_digit_for_less()
    {
        $code = VerifyCodeService::generate();
        $this->assertIsNumeric($code, "Generated code is not Numeric");
        $this->assertLessThanOrEqual(999999, $code, 'Generate Code is less than 999999');
        $this->assertGreaterThanOrEqual(100000, $code, 'Generate Code is greater than 100000');
    }

    public function test_verify_code_can_store()
    {
        $code = VerifyCodeService::generate();
        VerifyCodeService::store(1, $code);
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals($code, cache()->get('verify_code_1'));
    }

}
