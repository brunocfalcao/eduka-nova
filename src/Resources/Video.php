<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Resources\Actions\UploadVideo;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Video extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = \Eduka\Cube\Models\Video::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')->sortable(),

            Text::make('Vimeo Id', 'vimeo_id')
                ->hideWhenCreating(),

            Number::make('Duration')->displayUsing(function ($value) {
                if (! $value) {
                    return '';
                }

                if ($value < 60) {
                    return $value.' min';
                }

                $hours = (int) ($value / 60);
                $mins = $value % 60;

                return sprintf('%s hour %s mins', $hours, $mins);
            }),

            Boolean::make('Is Visible')->sortable(),
            Boolean::make('Is Active')->sortable(),
            Boolean::make('Is Free')->sortable(),

            Panel::make('Metadata & Social', [
                Text::make('Title', 'meta_title')
                    ->rules('nullable', 'max:250')
                    ->hideFromIndex(),

                Textarea::make('Description', 'meta_description')
                    ->hideFromIndex()
                    ->rules('nullable', 'max:250'),

                Text::make('Canonical URL', 'meta_canonical_url')
                    ->hideFromIndex()
                    ->rules('nullable', 'max:250', 'url'),
            ]),

            BelongsTo::make('Chapter', 'chapter', Chapter::class)
                ->searchable()
                ->showCreateRelationButton(),

            BelongsToMany::make('Variants', 'variants', Variant::class)
                ->fields(function () {
                    return [
                        Number::make('Index'),
                    ];
                }),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            new UploadVideo,
        ];
    }
}
