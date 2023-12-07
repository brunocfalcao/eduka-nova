<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Tag extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Tag::class;

    public static $title = 'name';

    public static $search = [
        'name',
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

            Text::make('Name'),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
