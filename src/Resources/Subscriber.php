<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Subscriber extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Subscriber::class;

    public static $title = 'email';

    public static $search = [
        'email',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->whereIn(
            'course_id',
            $request->user()
                    ->courses
                    ->pluck('id')
        );
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Email::make('Email'),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
