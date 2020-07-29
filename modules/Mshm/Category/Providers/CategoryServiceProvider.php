<?php

namespace Mshm\Category\Providers;

use Carbon\Laravel\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/categories_routes.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views/','Categories');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

}
