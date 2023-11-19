<?php

namespace Eduka\Nova;

use Eduka\Nova\Resources\Chapter;
use Eduka\Nova\Resources\Coupon;
use Eduka\Nova\Resources\Course;
use Eduka\Nova\Resources\CustomLink;
use Eduka\Nova\Resources\Dashboards\Main;
use Eduka\Nova\Resources\Domain;
use Eduka\Nova\Resources\Order;
use Eduka\Nova\Resources\Series;
use Eduka\Nova\Resources\Subscriber;
use Eduka\Nova\Resources\Tag;
use Eduka\Nova\Resources\User;
use Eduka\Nova\Resources\Variant;
use Eduka\Nova\Resources\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Main::class)->icon('chart-bar'),

                MenuSection::make('Students', [
                    MenuItem::resource(User::class),
                ])->icon('user'),

                MenuSection::make('Learning', [
                    MenuItem::resource(Course::class),
                    MenuItem::resource(Variant::class),
                    MenuItem::resource(Series::class),
                    MenuItem::resource(Chapter::class),
                    MenuItem::resource(Video::class),
                ])->icon('document-text'),

                MenuSection::make('Store', [
                    MenuItem::resource(Order::class),
                    MenuItem::resource(Coupon::class),
                ]),

                MenuSection::make('Others', [
                    MenuItem::resource(Tag::class),
                    MenuItem::resource(CustomLink::class),
                    MenuItem::resource(Domain::class),
                    MenuItem::resource(Subscriber::class),
                ]),

            ];
        });

        Field::macro('capitalizeFirst', function () {
            return $this->displayUsing(function ($value) {
                return Str::ucfirst($value);
            });
        });
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
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

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \Eduka\Nova\Resources\Dashboards\Main,
        ];
    }

    protected function resources()
    {
        Nova::resourcesIn(__DIR__.'/Resources');

        Nova::resources([
            User::class,
            Course::class,
            Domain::class,
            Series::class,
            Video::class,
            Coupon::class,
            Chapter::class,
            Order::class,
            Subscriber::class,
            Tag::class,
            CustomLink::class,
            Variant::class,
        ]);
    }
}
