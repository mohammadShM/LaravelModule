<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */

/** @noinspection SenselessProxyMethodInspection */

namespace Mshm\Payment\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Mshm\Payment\Gateways\Gateway;
use Mshm\Payment\Gateways\Zarinpal\ZarinpalAdapter;

class PaymentServiceProvider extends ServiceProvider
{

    public string $namespace = "Mshm\Payment\Http\Controllers";

    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . "/../Database/Migrations");
        Route::middleware("web")->namespace($this->namespace)
            ->group(__DIR__ . "/../Routes/payment_routes.php");
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function boot()
    {
        $this->app->singleton(Gateway::class, function ($app) {
            return new ZarinpalAdapter();
        });
//        Course::resolveRelationUsing("payments",function ($courseModel){
//            return $courseModel->morphMany(Payment::class,'paymentable');
//        });
    }

}
