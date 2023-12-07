<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Domain extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Domain::class;

    public static $title = 'name';

    public static $search = [
        'name',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
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
