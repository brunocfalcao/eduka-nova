<?php

namespace Eduka\Nova;

use Eduka\Abstracts\Classes\EdukaServiceProvider;
use Eduka\Nova\Resources\Backend;
use Eduka\Nova\Resources\Chapter;
use Eduka\Nova\Resources\Course;
use Eduka\Nova\Resources\Dashboards\CourseInsights;
use Eduka\Nova\Resources\Episode;
use Eduka\Nova\Resources\Link;
use Eduka\Nova\Resources\Order;
use Eduka\Nova\Resources\RequestLog;
use Eduka\Nova\Resources\Series;
use Eduka\Nova\Resources\Student;
use Eduka\Nova\Resources\Subscriber;
use Eduka\Nova\Resources\Tag;
use Eduka\Nova\Resources\Variant;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;

class EdukaNovaServiceProvider extends EdukaServiceProvider
{
    public function boot()
    {
        Nova::dashboards([
            new CourseInsights(),
        ]);

        $items = [];

        $items['course'] = MenuItem::resource(Course::class)
            ->name('Courses')
            ->withBadge('done', 'info');

        $items['backend'] = MenuItem::resource(Backend::class)
            ->name('Backends')
            ->withBadge('done', 'info');

        $items['variant'] = MenuItem::resource(Variant::class)
            ->name('Variants');

        $items['chapter'] = MenuItem::resource(Chapter::class)
            ->name('Chapters');

        $items['link'] = MenuItem::resource(Link::class)
            ->name('Links');

        $items['eduka_request_log'] = MenuItem::resource(RequestLog::class)
            ->name('Request Logs');

        $items['student'] = MenuItem::resource(Student::class)
            ->name('Students');

        Nova::mainMenu(function (Request $request) use ($items) {
            return [
                MenuSection::dashboard(CourseInsights::class)
                    ->icon('episode-camera'),

                MenuSection::make('Entities', [

                    $items['course'],
                    $items['backend'],
                    $items['variant'],
                    $items['chapter'],

                    MenuItem::resource(Episode::class),

                    $items['link'],

                    MenuItem::resource(Order::class),
                    MenuItem::resource(Series::class),
                    MenuItem::resource(Tag::class),
                    MenuItem::resource(Subscriber::class),
                    $items['student'],
                    $items['eduka_request_log'],
                ])->icon('student'),
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
            Student::class,
            Variant::class,
            Episode::class,
            Backend::class,
            RequestLog::class,
        ]);
    }

    public function register()
    {
        //
    }
}
