<?php

namespace Mshm\Dashboard\Providers;

use Carbon\Laravel\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/dashboard_routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Dashboard');
        $this->mergeConfigFrom(__DIR__ . '/../Config/sidebar.php', 'sidebar');
    }

    public function boot()
    {
        $this->app->booted(function () {
            config()->set('sidebar.items.dashboard', [
                "icon" => "i-dashboard",
                "title" => "پیشخوان",
                "url" => route('home'),
            ]);
        });
    }

}
