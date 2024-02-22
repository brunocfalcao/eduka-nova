<?php

namespace Eduka\Nova\Resources;

use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\KeyValue;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdUUID;
use Eduka\Nova\Resources\Fields\EdImage;
use Eduka\Cube\Models\Video as VideoModel;
use Eduka\Nova\Resources\Fields\EdHasMany;
use Eduka\Nova\Resources\Filters\ByCourse;
use Laravel\Nova\Http\Requests\NovaRequest;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Actions\UploadVideo;
use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Brunocfalcao\LaravelNovaHelpers\Traits\DefaultAscPKSorting;

class Video extends EdukaResource
{
    use DefaultAscPKSorting;

    public static $model = \Eduka\Cube\Models\Video::class;

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
            // Confirmed.
            EdID::make(),

            // Confirmed.
            Text::make('Name')
                ->rules($this->model()->rule('name')),

            // Confirmed.
            Text::make('Description')
                ->hideFromIndex()
                ->rules($this->model()->rule('description')),

            // Confirmed.
            EdBelongsTo::make('Course', 'course', Course::class)
                ->rules($this->model()->rule('course_id'))
                ->hideFromIndex(),

            // Confirmed.
            EdBelongsTo::make('Chapter', 'chapter', Chapter::class)
                ->sortable()
                ->rules($this->model()->rule('chapter_id')),

            Number::make('Index', 'index')
                ->sortable()
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

            KeyValue::make('Meta data (name)', 'meta_names')
                ->rules($this->model()->rule('meta_names')),

            KeyValue::make('Meta data (property)', 'meta_properties')
                ->rules($this->model()->rule('meta_properties')),

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
