<?php

namespace Eduka\Nova;

use Brunocfalcao\EdukaNova\Http\Middleware\Authorize;
use Eduka\Nova\Resources\Chapter;
use Eduka\Nova\Resources\Course;
use Eduka\Nova\Resources\Subscriber;
use Eduka\Nova\Resources\User;
use Eduka\Nova\Resources\Video;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class EdukaNovaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerResources();
        $this->loadViews();
        $this->publishResources();

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            //
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function publishResources()
    {
        $this->publishes([
            __DIR__.'/../resources/overrides/' => base_path('/'),
        ]);
    }

    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'eduka-nova');
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/eduka-nova')
                ->group(__DIR__.'/../routes/api.php');
    }

    protected function registerResources()
    {
        Nova::resources([
            User::class,
            Subscriber::class,
            Course::class,
            Chapter::class,
            Video::class,

        ]);
    }
}
