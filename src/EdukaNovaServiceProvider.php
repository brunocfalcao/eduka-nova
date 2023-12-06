<?php

namespace Eduka\Nova;

use Eduka\Abstracts\Classes\EdukaServiceProvider;
use Eduka\Nova\Resources\Chapter;
use Eduka\Nova\Resources\Coupon;
use Eduka\Nova\Resources\Course;
use Eduka\Nova\Resources\Dashboards\Main;
use Eduka\Nova\Resources\Domain;
use Eduka\Nova\Resources\Link;
use Eduka\Nova\Resources\Order;
use Eduka\Nova\Resources\Series;
use Eduka\Nova\Resources\Subscriber;
use Eduka\Nova\Resources\Tag;
use Eduka\Nova\Resources\User;
use Eduka\Nova\Resources\Variant;
use Eduka\Nova\Resources\Video;
use Eduka\Nova\Resources\VideoStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
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
                    MenuItem::resource(Link::class),
                    MenuItem::resource(Domain::class),
                    MenuItem::resource(Subscriber::class),
                    MenuItem::resource(VideoStorage::class),
                ]),

            ];
        });

        Field::macro('capitalizeFirst', function () {
            return $this->displayUsing(function ($value) {
                return Str::ucfirst($value);
            });
        });

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
            Link::class,
            Variant::class,
            VideoStorage::class
        ]);
    }

    public function register()
    {
        //
    }
}
