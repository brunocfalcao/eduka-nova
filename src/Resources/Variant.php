<?php

namespace Eduka\Nova\Resources;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Illuminate\Support\Str;

class Variant extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = \Eduka\Cube\Models\Variant::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'canonical';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'canonical',
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

            Hidden::make('Uuid')->default(function ($request) {
                return Str::orderedUuid();
            }),

            // form fields
            Panel::make('Basic info', [
                Text::make('Canonical')
                    ->rules('required', 'max:250')
                    ->sortable(),

                Boolean::make('Default', 'is_default')
                    ->rules('boolean'),

                Textarea::make('Description', 'description')
                    ->rules('nullable', 'max:250'),
            ]),

            Panel::make('Lemon Squeezy', [
                Text::make('Variant ID', 'lemonsqueezy_variant_id')
                    ->rules('required', 'string')
                    ->hideFromIndex(),

                Number::make('Price override', 'lemonsqueezy_price_override')
                    ->rules('nullable', 'numeric'),
            ]),

            // Relations

            BelongsTo::make('Course', 'course', Course::class),

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
        return [];
    }
}
