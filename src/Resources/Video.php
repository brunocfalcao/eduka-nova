<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Eduka\Cube\Models\Video as VideoModel;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Actions\UploadVideo;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdHasMany;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdImage;
use Eduka\Nova\Resources\Fields\EdUUID;
use Eduka\Nova\Resources\Filters\ByCourse;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Video extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Video::class;

    public static $with = ['course'];

    public function title()
    {
        $video = VideoModel::with('chapter')->find($this->id);

        return $this->name.($video->chapter ? " ({$video->chapter->name})" : '');
    }

    public static $search = [
        'name', 'description',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Name')
                ->rules($this->model()->rule('name')),

            Text::make('Description')
                ->hideFromIndex()
                ->rules($this->model()->rule('description')),

            EdBelongsTo::make('Course', 'course', Course::class)
                ->rules($this->model()->rule('course_id'))
                ->hideFromIndex(),

            EdBelongsTo::make('Chapter', 'chapter', Chapter::class)
                ->rules($this->model()->rule('chapter_id')),

            Number::make('Index', 'index')
                ->hideFromIndex()
                ->rules($this->model()->rule('index')),

            EdUUID::make('UUID'),

            Canonical::make()
                ->readonly()
                ->onlyOnDetail(),

            EdImage::make('SEO Image', 'filename')
                ->hideFromIndex()
                ->rules($this->model()->rule('filename')),

            Text::make('Vimeo Id', 'vimeo_id')
                ->rules($this->model()->rule('vimeo_id'))
                ->hideFromIndex()
                ->hideWhenCreating(),

            Number::make('Duration (in secs)', 'duration')->displayUsing(function ($value) {
                return human_duration($value);
            })
                ->rules($this->model()->rule('duration')),

            Boolean::make('Is Visible'),
            Boolean::make('Is Active'),
            Boolean::make('Is Free'),

            KeyValue::make('Meta data', 'meta')
                ->rules($this->model()->rule('meta')),

            // Confirmed.
            EdHasMany::make('Links', 'links', Link::class),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [
            UploadVideo::make()->sole(),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new ByCourse(),
        ];
    }
}
