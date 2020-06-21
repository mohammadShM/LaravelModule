<?php

namespace Mshm\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Mshm\User\Mail\VerifyCodeMail;
use Mshm\User\Services\VerifyCodeService;

class VerifyMailNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $code = VerifyCodeService::generate();
        VerifyCodeService::store($notifiable->id, $code);
        return (new VerifyCodeMail($code))
            ->to($notifiable->email);
    }

}
