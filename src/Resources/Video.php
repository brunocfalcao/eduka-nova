<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Resources\Actions\MakeActive;
use Eduka\Nova\Resources\Actions\MakeFree;
use Eduka\Nova\Resources\Actions\MakeInactive;
use Eduka\Nova\Resources\Actions\MakeInvisible;
use Eduka\Nova\Resources\Actions\MakePaid;
use Eduka\Nova\Resources\Actions\MakeVisible;
use Eduka\Nova\Resources\Actions\UploadVideo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
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

            Text::make('Vimeo', 'vimeo_id'),

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

                Text::make('Description', 'meta_description')
                    ->hideFromIndex()
                    ->rules('nullable', 'max:250'),

                Text::make('Canonical URL', 'meta_canonical_url')
                    ->hideFromIndex()
                    ->rules('nullable', 'max:250', 'url'),
            ]),

            BelongsToMany::make('Chapters', 'chapters', Chapter::class)
                ->fields(function ($request, $relatedModel) {
                    return [
                        Number::make('Index'),
                    ];
                })
                ->searchable()
                ->showCreateRelationButton(),
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
            (new UploadVideo)
                ->showInline()
                // ->sole() // Important, allows this action to be ran on a single instance
                ->confirmText('Upload video file'),

            (new MakeVisible)->showInline(),
            (new MakeFree)->showInline(),
            (new MakeActive)->showInline(),

            (new MakeInvisible),
            (new MakePaid),
            (new MakeInactive),

        ];
    }
}
