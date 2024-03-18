<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdDate;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdImage;
use Eduka\Nova\Resources\Fields\Timestamp;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Course extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Course::class;

    public static $title = 'name';

    public static $search = [
        'name', 'description',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            // Confirmed.
            EdID::make(),

            // Confirmed.
            Text::make('Name')
                ->helpInfo('Your course name. Make it worth :)')
                ->rules($this->model()->rule('name')),

            Textarea::make('Description')
                ->helpInfo('A more elaborated description of your course, mostly for SEO fields')
                ->hideFromIndex()
                ->rules($this->model()->rule('description')),

            EdBelongsTo::make('Admin user', 'admin', Student::class),

            // Confirmed.
            EdImage::make('SEO Image', 'filename')
                ->hideFromIndex()
                ->rules($this->model()->rule('filename')),

            // Confirmed.
            Canonical::make()
                ->helpInfo('The vite.config.js should have the course canonical value. Autogenerated if is empty')
                ->hideFromIndex(),

            // Confirmed.
            Text::make('Domain')
                ->helpInfo('The URL, without HTTPS, e.g.: masteringnova.com')
                ->rules($this->model()->rule('domain')),

            // Confirmed.
            EdBelongsTo::make('Backend', 'backend', Backend::class)
                ->helpInfo('The backend that will be shown for the students')
                ->hideFromIndex()
                ->rules($this->model()->rule('backend')),

            // Confirmed.
            Text::make('Service Provider class', 'provider_namespace')
                ->helpInfo('E.g.: MasteringNovaOrion\MasteringNovaOrionServiceProvider')
                ->helpWarning('Please ensure the namespace class exists before creating the backend')
                ->hideFromIndex()
                ->rules($this->model()->rule('provider_namespace')),

            Panel::make('Course lifecycle dates', [
                // Confirmed.
                EdDate::make('Prelaunched at')
                    ->helpInfo('The zero-date for the announcement of your course<br/>A landing page that will get early interested subscribers')
                    ->rules($this->model()->rule('prelaunched_at')),
                Timestamp::make('Prelaunched at'),

                // Confirmed.
                EdDate::make('Launched at')
                    ->helpInfo('The course launch date. At this moment, the prelaunched page will be disabled')
                    ->rules($this->model()->rule('launched_at')),
                Timestamp::make('Launched at'),

                // Confirmed.
                EdDate::make('Retired at')
                    ->helpInfo('Your course will be retired at this date, and a new retired page will appear. No further sales will be possible, but you can still have the course active, and allowing new episodes to be added')
                    ->rules($this->model()->rule('retired_at')),
                Timestamp::make('Retired at'),
            ]),

            // Confirmed. Computed.
            KeyValue::make('metas')
                ->helpInfo('SEO data, auto-generated each time its called')
                ->readonly(),

            // Confirmed.
            Panel::make('Timestamps', $this->timestamps($request)),

            // Confirmed.
            HasMany::make('Chapters', 'chapters', Chapter::class),

            // Confirmed.
            BelongsToMany::make('Students', 'students', Student::class),

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
            HasMany::make('Episodes', 'episodes', Episode::class),
        ];
    }
}
