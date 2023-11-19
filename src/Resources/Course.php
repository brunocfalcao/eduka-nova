<?php

namespace Eduka\Nova\Resources;

use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Course extends Resource
{
    /**
     * The model the resource corresponds to.
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

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount('users', 'chapters');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            // form fields
            Panel::make('Basic info', [
                Text::make('Name')
                    ->rules('required', 'max:250')
                    ->sortable(),

                DateTime::make('Launched', 'launched_at')
                    ->rules('nullable', 'datetime'),

                Boolean::make('Enable PPP', 'enable_purchase_power_parity')
                    ->rules('boolean'),

                Boolean::make('Dicommissioned', 'is_dicommissioned')
                    ->rules('boolean'),

                Text::make('Provider namespace')
                    ->hideFromIndex()
                    ->rules('required', 'max:250'),
            ]),

            Panel::make('Educator', [
                Text::make('Name', 'admin_name')
                    ->rules('nullable', 'max:250'),

                Text::make('Email', 'admin_email')
                    ->rules('nullable', 'max:250', 'email'),
            ]),

            Panel::make('Metadata & Social', [
                Text::make('Title', 'meta_title')
                    ->rules('nullable', 'max:250')
                    ->hideFromIndex(),

                Text::make('Canonical', 'canonical')
                    ->rules('nullable', 'max:250','url')
                    ->hideFromIndex(),

                Text::make('Description', 'meta_description')
                    ->hideFromIndex()
                    ->rules('nullable', 'max:250'),

                Text::make('Meta twitter alias', 'meta_twitter_alias')
                    ->hideFromIndex()
                    ->rules('nullable', 'max:250'),

                Text::make('Twitter handle', 'twitter_handle')
                    ->hideFromIndex()
                    ->rules('nullable', 'max:250'),
            ]),

            Panel::make('Lemon Squeezy', [
                Text::make('Store ID', 'lemonsqueezy_store_id')
                    ->rules('nullable', 'string')
                    ->hideFromIndex(),
            ]),

            // Relations
            HasMany::make('Domains', 'domains', Domain::class),

            HasMany::make('Chapters', 'chapters', Chapter::class),

            BelongsToMany::make('Users', 'users', User::class),

            Number::make('Registered users', 'users_count')
                ->onlyOnIndex()
                ->sortable(),

            Number::make('Chapters', 'chapters_count')
                ->onlyOnIndex()
                ->sortable(),
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
