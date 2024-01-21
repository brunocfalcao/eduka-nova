<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Link extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Link::class;

    public static $title = 'name';

    public static $search = [
        'name', 'url',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query
            ->upTo('videos')
            ->where('videos.created_by', $request->user()->id);
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Name')
                ->rules('required', 'max:100'),

            Text::make('Url')
                ->rules('required', 'url'),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
