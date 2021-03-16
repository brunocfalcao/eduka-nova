<?php

namespace Eduka\Nova\Resources;

use Eduka\Abstracts\EdukaResource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Video extends EdukaResource
{
    public static $indexDefaultOrder = ['created_at' => 'desc'];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Eduka\Cube\Models\Video::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title', 'introduction', 'details',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()
              ->sortable(),

            Text::make('Title', 'title')
                ->rules('required', 'string'),

            Text::make('Introduction', 'introduction')
                ->rules('required', 'string'),

            Textarea::make('Details', 'details')
                    ->rules('string', 'nullable'),

            Number::make('Index', 'index')
                ->readonly(),

            Boolean::make('Enabled?', 'is_enabled'),

            BelongsTo::make('Chapter', 'chapter', Chapter::class),

            DateTime::make('Published at', 'published_at')
                    ->rules('date', 'nullable'),

            DateTime::make('Archived at', 'archived_at')
                    ->rules('date', 'nullable'),

            DateTime::make('Created at', 'created_at')
                    ->readonly(),

            DateTime::make('Updated at', 'updated_at')
                    ->readonly(),

            DateTime::make('Deleted at', 'deleted_at')
                    ->readonly(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
