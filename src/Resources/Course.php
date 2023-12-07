<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdDateTime;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdTextarea;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
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
        return $query->whereIn(
            'id',
            $request->user()
                    ->courses
                    ->pluck('id')
        );
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Panel::make('Basic information', [
                Text::make('Name'),

                EdDateTime::make('Launched', 'launched_at'),

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

                EdTextarea::make('Description', 'meta_description')
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

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
