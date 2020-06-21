<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mshm\User\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_user_can_login_by_email()
    {
        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => bcrypt('A!123a'),
        ]);
        $this->post(route('login'),[
            'email'=>$user->email,
            'password'=>'A!123a'
        ]);
        $this->assertAuthenticated();
    }

    public function test_user_can_login_by_mobile()
     {
         $user = User::create([
             'name' => $this->faker->name,
             'email' => $this->faker->safeEmail,
             'mobile' => '9198765432',
             'password' => bcrypt('A!123a'),
         ]);
         $this->post(route('login'),[
             'email'=>$user->mobile,
             'password'=>'A!123a'
         ]);
         $this->assertAuthenticated();
     }

}
