<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdDateTime;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdImage;
use Eduka\Nova\Resources\Fields\HumanTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Course extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Course::class;

    public static $title = 'name';

    public static $search = [
        'name', 'description',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where(
            'id',
            $request->user()->course_id_as_admin
        );
    }

    public function fields(NovaRequest $request)
    {
        return [
            // Confirmed.
            EdID::make(),

            // Confirmed.
            Text::make('Name')
                ->rules($this->model()->rule('name')),

            EdImage::make('SEO Image', 'filename')
                ->rules($this->model()->rule('filename')),

            // Confirmed.
            Canonical::make()
                ->hideFromIndex()
                ->rules($this->model()->rule('canonical')),

            // Confirmed.
            Text::make('Domain')
                ->rules($this->model()->rule('domain')),

            // Confirmed.
            Text::make('Service Provider class', 'provider_namespace')
                ->hideFromIndex()
                ->rules($this->model()->rule('provider_namespace')),

            // Confirmed.
            EdDateTime::make('Prelaunched at')
                ->rules($this->model()->rule('prelaunched_at')),
            HumanTime::make('Prelaunched at'),

            // Confirmed.
            EdDateTime::make('Launched at')
                ->rules($this->model()->rule('launched_at')),
            HumanTime::make('Launched at'),

            // Confirmed.
            EdDateTime::make('Retired at')
                ->rules($this->model()->rule('retired_at')),
            HumanTime::make('Retired at'),

            // Confirmed.
            KeyValue::make('meta')
                ->rules($this->model()->rule('meta')),

            // Confirmed.
            Panel::make('Timestamps', $this->timestamps($request)),

            // Confirmed.
            HasOne::make('Admin User', 'adminUser', User::class)
                ->exceptOnForms(),

            // Confirmed.
            HasMany::make('Chapters', 'chapters', Chapter::class),

            // Confirmed.
            HasMany::make('Users', 'users', User::class),

            // Confirmed.
            HasMany::make('Orders', 'orders', Order::class),

            // Confirmed.
            HasMany::make('Subscribers', 'subscribers', Subscriber::class),

            // Confirmed.
            HasMany::make('Tags', 'tags', Tag::class),

            // Confirmed.
            HasMany::make('Variants', 'variants', Variant::class),

            // Confirmed.
            HasMany::make('Series', 'series', Series::class),

            // Confirmed.
            HasMany::make('Videos', 'videos', Video::class),
        ];
    }
}
