<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Chapter extends Resource
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
                ->rules('required', 'max:250'),

            Textarea::make('description')
                    ->rules('nullable', 'max:1000'),

            Number::make('Index')
                  ->rules('required', 'numeric'),

            HasMany::make('Videos', 'videos', Video::class),
        ];
    }
}
