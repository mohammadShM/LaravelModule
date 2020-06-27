<?php

namespace Mshm\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Mshm\User\Http\Requests\SendResetPasswordVerifyRequest;
use Mshm\User\Models\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showVerifyCodeRequestForm()
    {
        return view('User::Front.passwords.email');
    }


    public function sendVerifyCodeEmail(SendResetPasswordVerifyRequest $request)
    {
        // check if exists in database
        // TODO: userRepository
        $user = User::query()->where('email', $request->email)->first();
        // if true send email
        if ($user) {
            $user->sendResetPasswordRequestNotification();
        }
        // view verifyCodeForm

    }

}
