<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdDateTime;
use Eduka\Nova\Resources\Fields\EdID;
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
            Text::make('Name'),

            // Confirmed.
            Canonical::make(),

            // Confirmed.
            Text::make('Domain'),

            // Confirmed.
            Text::make('Service Provider class', 'provider_namespace'),

            // Confirmed.
            EdDateTime::make('Prelaunched at'),

            // Confirmed.
            EdDateTime::make('Launched at'),

            // Confirmed.
            EdDateTime::make('Retired at'),

            // Confirmed.
            KeyValue::make('meta'),

            // Confirmed.
            HasOne::make('Admin User', 'adminUser', User::class)
                ->exceptOnForms(),

            // Confirmed.
            HasMany::make('Chapters', 'chapters', Chapter::class),

            // Confirmed.
            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
