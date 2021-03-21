<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace Mshm\Course\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Mshm\Course\Listeners\RegisterUserInTheCourse;
use Mshm\Payment\Events\PaymentWasSuccessful;


class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        PaymentWasSuccessful::class => [
            RegisterUserInTheCourse::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }

}
