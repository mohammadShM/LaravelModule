<?php

namespace Tests\Unit;

use Mshm\User\Rules\ValidPassword;
use PHPUnit\Framework\TestCase;

class PasswordValidationTest extends TestCase
{

    public function test_password_should_not_be_less_than_6_character()
    {
        $result = (new ValidPassword())->passes('', '@M1m3');
        $this->assertEquals(0, $result);
    }

    public function test_password_should_include_sign_character()
    {
        $result = (new ValidPassword())->passes('', '22M1m3');
        $this->assertEquals(0, $result);
    }

    public function test_password_should_include_digit_character()
    {
        $result = (new ValidPassword())->passes('', 'MnMn@!');
        $this->assertEquals(0, $result);
    }

    public function test_password_should_include_Capital_character()
    {
        $result = (new ValidPassword())->passes('', '#$v1vbb55');
        $this->assertEquals(0, $result);
    }

    public function test_password_should_include_small_character()
    {
        $result = (new ValidPassword())->passes('', '#$BNM5655');
        $this->assertEquals(0, $result);
    }

    public function test_password_Access()
    {
        $result = (new ValidPassword())->passes('', '@M1m34');
        $this->assertEquals(1, $result);
    }

}
