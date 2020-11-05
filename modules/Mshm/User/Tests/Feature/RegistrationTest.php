<?php

namespace Mshm\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mshm\RolePermissions\Database\Seeds\RolePermissionTableSeeder;
use Mshm\User\Models\User;
use Mshm\User\Services\VerifyCodeService;
use Tests\TestCase;

class RegistrationTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function test_user_can_see_register_form()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
    }

    public function test_user_can_register()
    {
        $this->withoutExceptionHandling();
        $response = $this->post(route('register'), [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->email,
            'mobile' => '9198765432',
            'password' => '!!125asdAA@',
            'password_confirmation' => '!!125asdAA@',
        ]);
        $response->assertRedirect(route('home'));
        $this->assertCount(1, User::all());
    }

    /**
     * @return void
     */
    public function test_user_have_to_verify_account()
    {
        $this->registerNewUser();
        $response = $this->get(route('home'));
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_user_can_verify_account()
    {
        $user = User::create([
            'name' => $this->faker->firstName(),
            'email' => $this->faker->email,
            'password' => '!!125asdAA@',
        ]);
        $code = VerifyCodeService::generate();
        VerifyCodeService::store($user->id, $code,now()->addDay());
        auth()->loginUsingId($user->id);
        $this->assertAuthenticated();
        $this->post(route('verification.verify'), [
            'verify_code' => $code
        ]);
        $this->assertEquals(true, $user->fresh()->hasVerifiedEmail());
    }

    public function test_verified_user_can_see_home_page()
    {
        $this->seed(RolePermissionTableSeeder::class);
        $this->withoutExceptionHandling();
        $this->registerNewUser();
        $this->assertAuthenticated();
        /** @noinspection PhpUndefinedMethodInspection */
        auth()->user()->markEmailAsVerified();
        $response = $this->get(route('home'));
        $response->assertOk();
    }

    // util function for create new user for test
    private function registerNewUser()
    {
        return $this->post(route('register'), [
            'name' => 'HMN',
            'email' => 'hemoq11@gmail.com',
            'mobile' => '9367853698',
            'password' => 'As25@#',
            'password_confirmation' => 'As25@#',
        ]);
    }

}
