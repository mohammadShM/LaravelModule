<?php

namespace Mshm\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function test_user_can_see_user_password_request_form()
    {
        $response = $this->get(route('password.request'));
        $response->assertOk();
    }

    public function test_user_can_see_enter_verify_code_form_by_correct_email()
    {
        $this->call('get', route('password.sendVerifyCodeEmail'), [
            'email' => 'mohammad1@gmail.com'])->assertOk();
    }

    public function test_user_cannot_see_enter_verify_code_form_by_wrong_email()
    {
        $this->call('get', route('password.sendVerifyCodeEmail'), [
            'email' => 'mohammad1.com'])->assertStatus(302);
    }

    public function test_user_banned_after_6_attempt_to_reset_password()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('password.checkVerifyCode'), [
                'verify_code', 'email' => 'moh12345@gmail.com'
            ])->assertStatus(302);
        }
        $this->post(route('password.checkVerifyCode'), [
            'verify_code', 'email' => 'moh12345@gmail.com'
        ])->assertStatus(429);
    }

}
