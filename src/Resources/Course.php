<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Eduka\Nova\Abstracts\EdukaResource;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Course extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Course::class;

    public static $title = 'name';

    public static $search = [
        'name',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount('users');
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Panel::make('Basic information', [
                Text::make('Name'),

                DateTime::make('Launched', 'launched_at'),

                Boolean::make('Enable PPP', 'enable_purchase_power_parity'),

                Boolean::make('Decommissioned', 'is_decommissioned'),

                Text::make('Provider namespace')
                    ->hideFromIndex(),
            ]),

            Panel::make('Educator', [
                Text::make('Name', 'admin_name'),

                Text::make('Email', 'admin_email'),
            ]),

            Panel::make('Metadata & Social', [
                Text::make('Title', 'meta_title')
                    ->hideFromIndex(),

                Canonical::make(),

                Textarea::make('Description', 'meta_description')
                    ->hideFromIndex()
                    ->rules('nullable', 'max:250'),

                Text::make('Twitter alias', 'meta_twitter_alias')
                    ->hideFromIndex(),

                Text::make('Twitter handle', 'twitter_handle')
                    ->hideFromIndex(),
            ]),

            Panel::make('Lemon Squeezy', [
                Text::make('Store ID', 'lemon_squeezy_store_id')
                    ->rules('nullable', 'string')
                    ->hideFromIndex(),
            ]),

            Panel::make('Vimeo & BackBlaze', [
                Text::make('Vimeo Project ID', 'vimeo_project_id')
                    ->rules('nullable', 'string')
                    ->hideFromIndex(),

                Text::make('Backblaze Bucket', 'backblaze_bucket_name')
                    ->rules('nullable', 'string')
                    ->hideFromIndex(),
            ]),

            // Relations
            HasMany::make('Domains', 'domains', Domain::class),

            HasMany::make('Variants', 'variants', Variant::class),

            BelongsToMany::make('Users', 'users', User::class),

            Number::make('Registered users', 'users_count')
                ->onlyOnIndex()
                ->sortable(),

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
