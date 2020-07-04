<?php

namespace Mshm\Dashboard\Providers;

use Carbon\Laravel\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/dashboard_routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Dashboard');
    }

}
