<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class EdukaRequestLog extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\EdukaRequestLog::class;

    public static $globallySearchable = false;

    public function title()
    {
        return 'Request made on '.$this->created_at;
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where(
            'course_id',
            $request->user()->courseAsAdmin->id
        );
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            DateTime::make('Created At', 'created_at')
                ->displayUsing(function ($value) {
                    $timezone = config('app.timezone');

                    return $value->timezone($timezone)
                        ->format('F d, Y H:i');
                }),

            Text::make('Referrer'),

            Text::make('url'),

            Text::make('Route'),

            KeyValue::make('Parameters', 'parameters'),

            KeyValue::make('Middleware', 'middleware'),

            KeyValue::make('Payload', 'payload'),

            KeyValue::make('Headers', 'headers'),

            EdBelongsTo::make('Organization', 'organization', Organization::class),

            EdBelongsTo::make('Course', 'course', Course::class),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
