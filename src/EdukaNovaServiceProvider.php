<?php

namespace Eduka\Nova;

use Eduka\Abstracts\Classes\EdukaServiceProvider;
use Eduka\Nova\Resources\Chapter;
use Eduka\Nova\Resources\Course;
use Eduka\Nova\Resources\Dashboards\Main;
use Eduka\Nova\Resources\EdukaRequestLog;
use Eduka\Nova\Resources\Link;
use Eduka\Nova\Resources\Order;
use Eduka\Nova\Resources\Organization;
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

        $items = [];

        $items['course'] = MenuItem::resource(Course::class)
            ->name('Course (done)')
            ->withBadge('+5', 'info');

        $items['organization'] = MenuItem::resource(Organization::class)
            ->name('Organization (done)')
            ->withBadge('+2', 'warning');

        $items['variant'] = MenuItem::resource(Variant::class)
            ->name('Variants (done)');

        $items['chapter'] = MenuItem::resource(Chapter::class)
            ->name('Chapters (done)');

        $items['link'] = MenuItem::resource(Link::class)
            ->name('Links (done)');

        Nova::mainMenu(function (Request $request) use ($items) {
            return [
                MenuSection::dashboard(Main::class)->icon('chart-bar'),

                MenuSection::make('Entities', [

                    $items['course'],
                    $items['organization'],
                    $items['variant'],
                    $items['chapter'],

                    MenuItem::resource(Video::class),

                    $items['link'],

                    MenuItem::resource(Order::class),
                    MenuItem::resource(Series::class),
                    MenuItem::resource(Tag::class),
                    MenuItem::resource(Subscriber::class),
                    MenuItem::resource(User::class),
                    MenuItem::resource(EdukaRequestLog::class),
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
            Organization::class,
            EdukaRequestLog::class,
        ]);
    }

    public function register()
    {
        //
    }
}
