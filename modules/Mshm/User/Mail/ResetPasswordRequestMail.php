<?php

namespace Mshm\User\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Mshm\User\Models\User;

class ResetPasswordRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->markdown('User::mails.reset-password-verify-code')
            ->subject('وبسایت نمونه | بازیابی رمز عبور');
    }
}
