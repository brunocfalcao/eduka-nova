<?php

namespace Eduka\Nova\Resources;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Visit extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = \Eduka\Cube\Models\Visit::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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

            Panel::make('Basic info', [
                Number::make('User id')->sortable(),
                Number::make('Course id')->sortable(),
                Number::make('Goal id')->sortable(),
                Number::make('Goal id')->sortable(),
            ]),

            Panel::make('Routing', [
                Text::make('Url')->sortable(),
                Text::make('Path')->sortable(),
                Text::make('Route name')->sortable(),
            ]),

            Text::make('Currency')->sortable(),

            Panel::make('Referer', [
                Text::make('Utm Source', 'referrer_utm_source')->sortable(),
                Text::make('Domain', 'referrer_domain')->sortable(),
                Text::make('Campaign', 'referrer_campaign')->sortable(),
                Text::make('IP', 'ip')->sortable(),
            ]),

            Boolean::make('Bot', 'is_bot')
                ->displayUsing(fn ($isBot) => ! $isBot)
                ->sortable(),

            Panel::make('Region', [
                Text::make('Continent')->sortable(),
                Text::make('Continent code', 'continentCode')->sortable(),
                Text::make('Country')->sortable(),
                Text::make('Country code', 'countryCode')->sortable(),
                Text::make('Region', 'region')->sortable(),
                Text::make('Region name', 'regionName')->sortable(),
                Text::make('City')->sortable(),
                Text::make('District')->sortable(),
                Text::make('Zip')->sortable(),
                Text::make('Timezone')->sortable(),
                Number::make('Latitude')->sortable(),
                Text::make('Longitude')->sortable(),
            ]),

            DateTime::make('Created at'),

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
