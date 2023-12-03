<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Series extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Series::class;

    public static $title = 'name';

    public static $search = [
        'name',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount('videos');
    }

    public static function detailQuery(NovaRequest $request, $query)
    {
        return $query->withCount('videos');
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Name')
                ->rules('required', 'max:250')
                ->sortable(),

            Textarea::make('description')
                ->rules('nullable', 'max:255')
                ->hideFromIndex(),

            Number::make('Number of videos', 'videos_count')
                ->exceptOnForms()
                ->sortable(),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
