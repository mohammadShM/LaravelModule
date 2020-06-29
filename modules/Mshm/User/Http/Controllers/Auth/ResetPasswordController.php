<?php

namespace Mshm\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Mshm\User\Http\Requests\ChangePasswordRequest;
use Mshm\User\Services\UserService;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = RouteServiceProvider::HOME;

    /** @noinspection PhpUnused */
    public function showResetForm()
    {
        return view('User::Front.passwords.reset');
    }

    public function reset(ChangePasswordRequest $request)
    {
        UserService::changePassword(auth()->user(), $request->password);
        return redirect(route('home'));
    }

}
