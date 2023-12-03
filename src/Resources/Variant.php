<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Variant extends EdukaResource
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
            EdID::make(),

            Hidden::make('Uuid')->default(function ($request) {
                return Str::orderedUuid();
            }),

            // form fields
            Panel::make('Basic info', [
                Canonical::make(),

                Boolean::make('Default', 'is_default')
                    ->rules('boolean'),

                Textarea::make('Description', 'description')
                    ->rules('required', 'max:250'),
            ]),

            Panel::make('Lemon Squeezy', [
                Text::make('Variant ID', 'lemon_squeezy_variant_id')
                    ->rules('required', 'string')
                    ->hideFromIndex(),

                Number::make('Price override', 'lemon_squeezy_price_override')
                    ->rules('nullable', 'numeric'),
            ]),

            Panel::make('Timestamps', $this->timestamps($request)),
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
