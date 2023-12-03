<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdTextarea;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Chapter extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Chapter::class;

    public static $title = 'name';

    public static $search = [
        'name',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Name')
                ->rules('required'),

            EdTextarea::make('description'),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
