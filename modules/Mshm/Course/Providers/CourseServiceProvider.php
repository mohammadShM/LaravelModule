<?php

namespace Mshm\Course\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Mshm\Course\Models\Course;
use Mshm\Course\Models\Lesson;
use Mshm\Course\Models\Season;
use Mshm\Course\Policies\CoursePolicy;
use Mshm\Course\Policies\LessonPolicy;
use Mshm\Course\Policies\SeasonPolicy;
use Mshm\RolePermissions\Models\Permission;

class CourseServiceProvider extends ServiceProvider
{

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
        $this->loadRoutesFrom(__DIR__ . '/../Routes/courses_routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/seasons_routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/lessons_routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'Courses');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../Resources/Lang');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', "Courses");
        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Season::class, SeasonPolicy::class);
        Gate::policy(Lesson::class, LessonPolicy::class);
    }

    public function boot(): void
    {
        config()->set('sidebar.items.courses', [
            "icon" => "i-courses",
            "title" => "دوره ها",
            "url" => route('courses.index'),
            "permission" => [
                Permission::PERMISSION_MANAGE_COURSES,
                Permission::PERMISSION_MANAGE_OWN_COURSES,
            ],

        ]);
    }

}
