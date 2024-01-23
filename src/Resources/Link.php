<?php

namespace Eduka\Nova\Resources;

use Eduka\Cube\Models\Video;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Video as VideoResource;
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

    public static function indexQuery(NovaRequest $request, $query)
    {
        // Links only for the videos that are part of this course.
        return $query->whereIn(
            'video_id',
            Video::where(
                'course_id',
                $request->user()->course_id_as_admin
            )->pluck('id')
        );
    }

    public function fields(NovaRequest $request)
    {
        return [
            // Confirmed.
            EdID::make(),

            // Confimed.
            Text::make('Name'),

            // Confirmed.
            URL::make('Url')
                ->displayUsing(fn () => "{$this->url}"),

            // Confirmed.
            Panel::make('Timestamps', $this->timestamps($request)),

            // Confirmed.
            EdBelongsTo::make('Video', 'video', VideoResource::class),
        ];
    }
}
