<?php

namespace Mshm\RolePermissions\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Mshm\RolePermissions\Models\Permission;
use Mshm\RolePermissions\Models\Role;
use Mshm\RolePermissions\Policies\RolePermissionPolicy;

class RolePermissionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/role_permissions_routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'RolePermissions');
        $this->loadJsonTranslationsFrom(__DIR__ . "/../Resources/Lang");
        Gate::policy(Role::class, RolePermissionPolicy::class);
        // Gate before for all
        Gate::before(function ($user) {
            // return true and false and null
            return $user->hasPermissionTo(Permission::PERMISSION_SUPER_ADMIN) ? true : null;
        });
    }

    public function boot()
    {
        config()->set('sidebar.items.role-permissions', [
            "icon" => "i-role_permissions",
            "title" => "نقش های کاربری",
            "url" => route('role-permissions.index'),
        ]);
    }

}
