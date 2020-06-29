<?php

namespace Mshm\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Mshm\User\Http\Requests\ResetPasswordVerifyCodeRequest;
use Mshm\User\Http\Requests\SendResetPasswordVerifyRequest;
use Mshm\User\Repositories\UserRepo;
use Mshm\User\Services\VerifyCodeService;

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

    /** @noinspection PhpUnused */
    public function showVerifyCodeRequestForm()
    {
        return view('User::Front.passwords.email');
    }

    /** @noinspection PhpUnused */
    public function sendVerifyCodeEmail(SendResetPasswordVerifyRequest $request)
    {
        // use in RepositoryPattern (userRepo)
        $user = resolve(UserRepo::class)->findByEmail($request->email);
        // if true send email || check if code exists
        // check if exists in database
        if ($user && !VerifyCodeService::has($user->id)) {
            $user->sendResetPasswordRequestNotification();
        }
        // view verifyCodeForm
        return view('User::Front.passwords.enter-verify-code-form');
    }

    /** @noinspection PhpUnused */
    public function checkVerifyCode(ResetPasswordVerifyCodeRequest $request)
    {
        // use in RepositoryPattern (userRepo)
        $user = resolve(UserRepo::class)->findByEmail($request->email);
        if ($user == null || !VerifyCodeService::check($user->id, $request->verify_code)) {
            return back()->withErrors(['verify_code' => 'کد وارد شده معتبر نمیباشد!']);
        }
        auth()->loginUsingId($user->id);
        return redirect(route('password.showResetForm'));
    }

}
