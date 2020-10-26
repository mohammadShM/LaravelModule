<?php

namespace Mshm\User\Providers;

use DatabaseSeeder;
use Gate;
use Illuminate\Support\ServiceProvider;
use Mshm\User\Database\Seeds\UsersTableSeeder;
use Mshm\User\Http\Middleware\StoreUserIp;
use Mshm\User\Models\User;
use Mshm\User\Policies\UserPolicy;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/user_routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadFactoriesFrom(__DIR__ . '/../Database/Factories');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'User');
        $this->loadJsonTranslationsFrom(__DIR__ . "/../Resources/Views/Lang");
        config()->set('auth.providers.users.model', User::class);
        Gate::policy(User::class, UserPolicy::class);
        DatabaseSeeder::$seeders[] = UsersTableSeeder::class;
        // for get ip by middleware for set ip field in users table
        $this->app['router']->pushMiddlewareToGroup('web', StoreUserIp::class);
    }

    public function boot()
    {
        config()->set('sidebar.items.users', [
            "icon" => "i-users",
            "title" => "کاربران",
            "url" => route('users.index'),
        ]);
    }

}
