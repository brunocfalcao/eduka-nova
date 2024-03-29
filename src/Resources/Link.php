<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Episode as EpisodeResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Link extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Link::class;

    public static $title = 'name';

    public static $search = [
        'name', 'url',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            // Confirmed.
            EdID::make(),

            // Confimed.
            Text::make('Name')
                ->helpInfo('Link name, descriptive but might not appear everywhere')
                ->rules($this->model()->rule('name')),

            // Confirmed.
            URL::make('Url')
                ->rules($this->model()->rule('url'))
                ->helpInfo('E.g.: https://www.publico.pt - Needs to be a full URL')
                ->displayUsing(fn () => "{$this->url}"),

            // Confirmed.
            Panel::make('Timestamps', $this->timestamps($request)),

            // Confirmed.
            EdBelongsTo::make('Episode', 'episode', EpisodeResource::class)
                ->helpInfo('Related episode'),
        ];
    }
}
