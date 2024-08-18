<?php

namespace Eduka\Nova\Resources;

use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\BelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdUUID;
use Eduka\Nova\Resources\Fields\EdImage;
use Eduka\Nova\Resources\Fields\EdHasMany;
use Eduka\Nova\Resources\Filters\ByCourse;
use Laravel\Nova\Http\Requests\NovaRequest;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Cube\Models\Episode as EpisodeModel;
use Eduka\Nova\Resources\Actions\UploadEpisode;
use Eduka\Nova\Resources\Fields\EdBelongsToMany;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Brunocfalcao\LaravelNovaHelpers\Traits\DefaultDescPKSorting;

class Episode extends EdukaResource
{
    use DefaultDescPKSorting;

    public static $model = \Eduka\Cube\Models\Episode::class;

    public static function defaultOrderings($query)
    {
        // Useful if it's via resource to see the index ASC (E.g. from Chapters).
        if (via_resource()) {
            return $query->orderBy('episodes.index');
        } else {
            // Just use default ordering desc
            $model = $query->getModel();
            $table = $model->getTable();
            $keyName = $model->getKeyName();

            return $query->orderBy("{$table}.{$keyName}", 'desc');
        }
    }

    public function title()
    {
        $episode = EpisodeModel::with('chapter')->find($this->id);

        return $this->name.($episode->chapter ? " ({$episode->chapter->name})" : '');
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
                ->helpInfo('The Episode name (synced with Vimeo)')
                ->rules($this->model()->rule('name')),

            // Confirmed.
            Text::make('Description')
                ->helpInfo('A more detailed description about what the episode is about')
                ->hideFromIndex()
                ->rules($this->model()->rule('description')),

            // Confirmed.
            EdBelongsTo::make('Course', 'course', Course::class)
                ->helpInfo('Related Course, where the episode is part of')
                ->rules($this->model()->rule('course_id'))
                ->hideFromIndex(),

            // Confirmed.
            EdBelongsTo::make('Chapter', 'chapter', Chapter::class)
                ->helpInfo('Related Chapter, based on the previous selected course')
                ->dependsOn(['course'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                    $field->relatableQueryUsing(function (NovaRequest $request, $query) use ($formData) {
                        $query->where('course_id', $formData->course);
                    });
                })
                ->sortable()
                ->nullable()
                ->rules($this->model()->rule('chapter_id')),

            // Confirmed.
            Number::make('Index', 'index')
                ->helpInfo('If empty, will be given the next index of the related chapter')
                ->rules('numeric'),

            // Confirmed.
            EdUUID::make('UUID'),

            // Confirmed.
            Canonical::make()
                ->readonly()
                ->onlyOnDetail(),

            Images::make('Social image', 'default')
                ->conversionOnIndexView('thumbnail')
                ->withResponsiveImages(),

            // Confirmed.
            Text::make('Vimeo URI', 'vimeo_uri')
                ->readonly()
                ->hideFromIndex()
                ->canSee(function ($request) {
                    return ! is_null($this->duration);
                }),

            // Confirmed.
            Number::make('Duration (in secs)', 'duration')->displayUsing(function ($value) {
                return human_duration($value);
            })
                ->hideFromIndex()
                ->canSee(function ($request) {
                    return ! is_null($this->duration);
                })
                ->readonly(),

            Boolean::make('Uploaded?', function () {
                return ! is_null($this->vimeo_uri);
            }),

            // Confirmed.
            Boolean::make('Is Visible?', 'is_visible')
                ->helpInfo('Episode will appear on all collections where we ask what videos are active, to be rendered on screen'),

            // Confirmed.
            Boolean::make('Is Active?', 'is_active')
                ->helpInfo('Episode will be clickable, and playable/viewable'),

            // Confirmed.
            Boolean::make('Is Free?', 'is_free')
                ->helpInfo('In case the episode is marked as free, it will be automatically uploaded to Youtube'),

            KeyValue::make('Metas', 'metas')
                ->onlyOnDetail()
                ->helpInfo('SEO data, auto-generated each time its called'),

            // Confirmed.
            EdHasMany::make('Links', 'links', Link::class),

            // Confirmed.
            EdBelongsToMany::make('Series', 'series', Series::class),

            // Confirmed.
            EdBelongsToMany::make('Tags', 'tags', Tag::class),

            // Confirmed.
            EdBelongsToMany::make('Bookmarked', 'studentsThatBookmarked', Student::class),

            // Confirmed.
            EdBelongsToMany::make('Seen', 'studentsThatSaw', Student::class),

            // Confirmed.
            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [
            UploadEpisode::make()->sole(),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new ByCourse,
        ];
    }
}
