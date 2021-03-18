<?php

namespace Mshm\Front\Providers;

use Illuminate\Support\ServiceProvider;
use Mshm\Category\Models\Category;
use Mshm\Category\Repositories\CategoryRepo;
use Mshm\Course\Repositories\CourseRepo;

class FrontServiceProvider extends ServiceProvider
{

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/front_route.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Front');
        view()->composer('Front::layout.header', function ($view) {
            $categories = (new CategoryRepo())->tree();
            $view->with(compact('categories'));
        });
        view()->composer('Front::layout.latestCourses', function ($view) {
            $latestCourses = (new CourseRepo())->latestCourses();
            $view->with(compact('latestCourses'));
        });
    }

}
