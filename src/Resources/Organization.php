<?php

namespace Eduka\Nova\Resources;

use Eduka\Cube\Models\User;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdHasMany;
use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Organization extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Organization::class;

    public static $title = 'name';

    public static $search = [
        'name',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        // Organization that is part of the course where the user is admin.
        return $query->where(
            'id',
            User::firstWhere(
                'id',
                $request->user()->id
            )->courseAsAdmin->organization->id
        );
    }

    public function fields(NovaRequest $request)
    {
        return [
            // Confirmed.
            EdID::make(),

            // Confimed.
            Text::make('Name')
                ->rules($this->model()->rule('name')),

            // Confimed.
            Text::make('Description')
                ->rules($this->model()->rule('description')),

            // Confirmed.
            Text::make('Domain')
                ->rules($this->model()->rule('domain')),

            // Confimed.
            Text::make('Provider Namespace')
                ->rules($this->model()->rule('provider_namespace')),

            // Confirmed.
            Panel::make('Timestamps', $this->timestamps($request)),

            // Confirmed.
            EdHasMany::make('Courses', 'courses', Course::class),
        ];
    }
}