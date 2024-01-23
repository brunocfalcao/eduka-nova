<?php

namespace Eduka\Nova;

use Eduka\Abstracts\Classes\EdukaServiceProvider;
use Eduka\Nova\Resources\Chapter;
use Eduka\Nova\Resources\Course;
use Eduka\Nova\Resources\Dashboards\Main;
use Eduka\Nova\Resources\Link;
use Eduka\Nova\Resources\Order;
use Eduka\Nova\Resources\Series;
use Eduka\Nova\Resources\Subscriber;
use Eduka\Nova\Resources\Tag;
use Eduka\Nova\Resources\User;
use Eduka\Nova\Resources\Variant;
use Eduka\Nova\Resources\Video;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;

class EdukaNovaServiceProvider extends EdukaServiceProvider
{
    public function boot()
    {
        Nova::dashboards([
            new Main(),
        ]);

        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Main::class)->icon('chart-bar'),

                MenuSection::make('Entities', [
                    MenuItem::resource(Course::class)->name('Course (done)'),
                    MenuItem::resource(Variant::class),
                    MenuItem::resource(Chapter::class)->name('Chapters (done)'),
                    MenuItem::resource(Video::class),

                    MenuItem::resource(Link::class)->name('Links (done)'),
                    MenuItem::resource(Order::class),
                    MenuItem::resource(Series::class),
                    MenuItem::resource(Tag::class),
                    MenuItem::resource(Subscriber::class),
                    MenuItem::resource(User::class),
                ])->icon('user'),
            ];
        });

        Nova::resources([
            Chapter::class,
            Course::class,
            Link::class,
            Order::class,
            Series::class,
            Subscriber::class,
            Tag::class,
            User::class,
            Variant::class,
            Video::class,
        ]);
    }

    public function register()
    {
        //
    }
}
