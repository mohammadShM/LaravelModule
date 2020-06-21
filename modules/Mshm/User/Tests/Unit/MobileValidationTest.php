<?php

namespace Mshm\User\Tests\Unit;

use Mshm\User\Rules\ValidMobile;
use PHPUnit\Framework\TestCase;

class MobileValidationTest extends TestCase
{

    public function test_mobile_can_not_be_less_than_10_character()
    {
        $result = (new ValidMobile())->passes('', '939121413');
        $this->assertEquals(0, $result);
    }

    public function test_mobile_can_not_be_more_than_10_character()
    {
        $result = (new ValidMobile())->passes('', '93912141322');
        $this->assertEquals(0, $result);
    }

    public function test_mobile_should_start_by_9()
    {
        $result = (new ValidMobile())->passes('', '2391214132');
        $this->assertEquals(0, $result);
    }

    public function test_mobile_Access()
    {
        $result = (new ValidMobile())->passes('', '9391214132');
        $this->assertEquals(1, $result);
    }

}
