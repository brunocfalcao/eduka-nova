<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Filters\ByCourse;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class RequestLog extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\RequestLog::class;

    public static $search = [
        'referrer', 'url', 'route',
    ];

    public function title()
    {
        return 'Request made on '.
               $this->created_at
                   ->timezone(config('app.timezone'))
                   ->format('F d, Y H:i');
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            EdBelongsTo::make('User', 'student', User::class),

            EdBelongsTo::make('Backend', 'backend', Backend::class)
                ->hideFromIndex(),

            EdBelongsTo::make('Course', 'course', Course::class)
                ->hideFromIndex(),

            DateTime::make('Created At', 'created_at')
                ->displayUsing(function ($value) {
                    $timezone = config('app.timezone');

                    return $value->timezone($timezone)
                        ->format('F d, Y H:i');
                }),

            Text::make('Referrer', 'referrer')
                ->displayUsing(function ($value) {
                    return extract_host_from_url($value);
                }),

            Text::make('url')
                ->hideFromIndex(),

            Text::make('Route'),

            KeyValue::make('Parameters', 'parameters'),

            KeyValue::make('Middleware', 'middleware'),

            KeyValue::make('Payload', 'payload'),

            KeyValue::make('Headers', 'headers'),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new ByCourse(),
        ];
    }
}
