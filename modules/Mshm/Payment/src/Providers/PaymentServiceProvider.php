<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */

/** @noinspection SenselessProxyMethodInspection */

namespace Mshm\Payment\Providers ;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider{

    public function register()
    {
        $this->loadMigrationsFrom(__DIR__."/../Database/Migrations");
    }

    public function boot()
    {

    }

}
