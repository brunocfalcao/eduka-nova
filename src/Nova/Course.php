<?php

namespace Eduka\Nova\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;

class Course extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     */
    public static $model = \Eduka\Cube\Models\Course::class;

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

    public static function indexQuery(NovaRequest $request, $query) {
        return $query->withCount('users');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')->sortable(),


            Stack::make('Admin', [
                Text::make('Name','admin_name'),
                Text::make('Email','admin_email'),
            ]),
            Boolean::make('Is Decommissioned'),

            Boolean::make('Launched', 'launched_at')->displayUsing(function($launchedAt){
                if (!$launchedAt) {
                    return false;
                }

                $launchedAt = Carbon::parse($launchedAt);

                return now()->gte($launchedAt);
            }),

            Currency::make('Price', 'course_price')->currency(config('eduka.currency')),

            HasMany::make('Domains','domains', Domain::class),

            BelongsToMany::make('Users','users', User::class),

            Number::make('Registered users', 'users_count')
                ->onlyOnIndex()
                ->sortable(),

            Boolean::make('PPP Enabled', 'enable_purchase_power_parity'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
