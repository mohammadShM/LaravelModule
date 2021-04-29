<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */

/** @noinspection SenselessProxyMethodInspection */

namespace Mshm\Payment\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Mshm\Payment\Gateways\Gateway;
use Mshm\Payment\Gateways\Zarinpal\ZarinpalAdapter;
use Mshm\RolePermissions\Models\Permission;

class PaymentServiceProvider extends ServiceProvider
{

    public string $namespace = "Mshm\Payment\Http\Controllers";

    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . "/../Database/Migrations");
        Route::middleware("web")->namespace($this->namespace)
            ->group(__DIR__ . "/../Routes/payment_routes.php");
        $this->loadViewsFrom(__DIR__."/../Resources/Views","Payment");
        $this->loadJsonTranslationsFrom(__DIR__."/../Resources/Lang");
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
        config()->set('sidebar.items.payments', [
            "icon" => "i-transactions",
            "title" => "تراکنش ها",
            "url" => route('payments.index'),
            "permission" => [
                Permission::PERMISSION_MANAGE_COURSES,
            ],

        ]);
    }

}
