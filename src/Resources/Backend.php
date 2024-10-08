<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdHasMany;
use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Backend extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Backend::class;

    public static $title = 'name';

    public static $search = ['name'];

    public function fields(NovaRequest $request)
    {
        return [
            // Confirmed.
            EdID::make(),

            // Confimed.
            Text::make('Name', 'name')
                ->rules($this->model()->rule('name')),

            // Confimed.
            Text::make('Description', 'description')
                ->rules($this->model()->rule('description')),

            // Confirmed.
            Text::make('Domain', 'domain')
                ->rules($this->model()->rule('domain')),

            // Confimed.
            Text::make('Provider Namespace', 'service_provider_class')
                ->helpWarning('Please ensure the namespace class exists before creating the backend')
                ->rules($this->model()->rule('service_provider_class')),

            // Confirmed.
            Panel::make('Timestamps', $this->timestamps($request)),

            // Confirmed.
            EdHasMany::make('Courses', 'courses', Course::class),

            // Confirmed.
            EdHasMany::make('Request Logs', 'requestLogs', RequestLog::class),
        ];
    }
}
