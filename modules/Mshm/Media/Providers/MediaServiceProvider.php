<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace Mshm\Media\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{

    protected $namespace = 'Mshm\Media\Http\Controllers';

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    public function register()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../Routes/media_routes.php');
//        $this->loadRoutesFrom(__DIR__ . '/../Routes/media_routes.php');
//        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'Media');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
//        $this->loadJsonTranslationsFrom(__DIR__ . '/../Resources/Lang');
//        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang',"Media");
        $this->mergeConfigFrom(__DIR__ . "/../Config/mediaFile.php", 'mediaFile');
    }

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    public function boot()
    {
        config()->set('sidebar.items.courses', [
            "icon" => "i-courses",
            "title" => "دوره ها",
            "url" => route('courses.index'),
        ]);
    }

}
