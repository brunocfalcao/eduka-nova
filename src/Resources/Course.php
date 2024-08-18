<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Brunocfalcao\LaravelNovaHelpers\Traits\NovaHelpers;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdDate;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdUUID;
use Eduka\Nova\Resources\Fields\Timestamp;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Course extends EdukaResource
{
    use NovaHelpers;

    public static $model = \Eduka\Cube\Models\Course::class;

    public static $title = 'name';

    public static $search = ['name', 'description'];

    public function fields(NovaRequest $request)
    {
        return [
            // Confirmed.
            EdID::make(),

            // Confirmed.
            Text::make('Name', 'name')
                ->helpInfo('Your course name. Make it worth :)')
                ->rules($this->model()->rule('name')),

            Textarea::make('Description', 'description')
                ->helpInfo('A more elaborated description of your course, mostly for SEO fields')
                ->hideFromIndex()
                ->rules($this->model()->rule('description')),

            EdBelongsTo::make('Admin user', 'admin', Student::class)
                ->helpInfo('You need to first create an user, and then connect it to the Course as the user that can login into Nova'),

            Images::make('Logo')
                ->conversionOnIndexView('thumbnail')
                ->withResponsiveImages(),

            // Confirmed.
            Text::make('Twitter handle', 'twitter_handle')
                ->hideFromIndex()
                ->helpInfo('Twitter handle without the @')
                ->rules($this->model()->rule('twitter_handle')),

            // Confirmed.
            KeyValue::make('Theme', 'theme')
                ->rules($this->model()->rule('theme')),

            // Confirmed.
            Canonical::make()
                ->helpInfo('The vite.config.js should have the course canonical value. Autogenerated if is empty')
                ->hideFromIndex(),

            // Confirmed
            EdUUID::make('UUID'),

            // Confirmed.
            Text::make('Domain', 'domain')
                ->helpInfo('The URL, without HTTPS, e.g.: masteringnova.com')
                ->rules($this->model()->rule('domain')),

            // Confirmed.
            EdBelongsTo::make('Backend', 'backend', Backend::class)
                ->helpInfo('The backend that will be shown for the students')
                ->hideFromIndex()
                ->rules($this->model()->rule('backend')),

            // Confirmed.
            Text::make('Service Provider class', 'service_provider_class')
                ->helpInfo('E.g.: MasteringNovaOrion\MasteringNovaOrionServiceProvider')
                ->helpWarning('Please ensure the namespace class exists before creating the backend')
                ->hideFromIndex()
                ->rules($this->model()->rule('service_provider_class')),

            Text::make('Clarity code', 'clarity_code')
                ->hideFromIndex()
                ->helpInfo('Microsoft Clarity script code, auto-generated'),

            Boolean::make('Is PPP enabled?', 'is_ppp_enabled')
                ->helpInfo('If checked, then the LMS will present a message to the visitor with a discount coupon code based on the visitor country'),

            Panel::make('Course lifecycle dates', [
                // Confirmed.
                EdDate::make('Prelaunched at', 'prelaunched_at')
                    ->helpInfo('The zero-date for the announcement of your course<br/>A landing page that will get early interested subscribers')
                    ->rules($this->model()->rule('prelaunched_at')),
                Timestamp::make('Prelaunched at'),

                // Confirmed.
                EdDate::make('Launched at', 'launched_at')
                    ->helpInfo('The course launch date. At this moment, the prelaunched page will be disabled')
                    ->rules($this->model()->rule('launched_at')),
                Timestamp::make('Launched at'),

                // Confirmed.
                EdDate::make('Retired at', 'retired_at')
                    ->helpInfo('Your course will be retired at this date, and a new retired page will appear. No further sales will be possible, but you can still have the course active, and allowing new episodes to be added')
                    ->rules($this->model()->rule('retired_at')),
                Timestamp::make('Retired at'),
            ]),

            // Confirmed.
            Boolean::make('Is Active?', 'is_active')
                ->helpInfo('This is a master state switch, if not active then the course will display the disabled page'),

            // Confirmed.
            Select::make('Progress', 'progress')->options([
                '0' => '0',
                '25' => '25',
                '50' => '50',
                '75' => '75',
                '100' => '100',
            ])->helpInfo('Used in the prelaunched page, normally to show the progress of the course launch')
                ->displayUsingLabels(),

            // Confirmed.
            Text::make('LS Store ID', 'lemon_squeezy_store_id')
                ->hideFromIndex()
                ->helpInfo('Lemon Squeezy Store Id')
                ->rules($this->model()->rule('lemon_squeezy_store_id')),

            // Confirmed.
            Text::make('LS API Key', 'lemon_squeezy_api_key')
                ->hideFromIndex()
                ->helpInfo('Lemon Squeezy API Key')
                ->rules($this->model()->rule('lemon_squeezy_api_key')),

            // Confirmed.
            Text::make('LS Hash', 'lemon_squeezy_hash')
                ->hideFromIndex()
                ->helpInfo('Lemon Squeezy Hash')
                ->rules($this->model()->rule('lemon_squeezy_hash')),

            // Confirmed.
            Text::make('Vimeo URI', 'vimeo_uri')
                ->onlyOnDetail()
                ->helpInfo('Vimeo URI'),

            // Confirmed.
            Text::make('Vimeo folder id', 'vimeo_folder_id')
                ->onlyOnDetail()
                ->helpInfo('Vimeo folder ID'),

            // Confirmed.
            KeyValue::make('Metas', 'metas')
                ->onlyOnDetail()
                ->helpInfo('SEO data, auto-generated each time its called'),

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
