<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdUUID;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Variant extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Variant::class;

    public static $title = 'canonical';

    public static $search = [
        'canonical', 'description',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            // Confirmed.
            EdID::make(),

            Text::make('Name')
                ->rules($this->model()->rule('name')),

            // Confirmed.
            EdUUID::make('UUid'),

            // Confirmed.
            Canonical::make()
                ->exceptOnForms(),

            // Confirmed.
            Text::make('Description', 'description')
                ->rules($this->model()->rule('description')),

            // Confirmed.
            Boolean::make('Default', 'is_default')
                ->rules($this->model()->rule('is_default'))
                ->helpInfo('If there is no default variant for the course. You can make it default here'),

            Text::make('LS variant ID', 'lemon_squeezy_variant_id')
                ->rules($this->model()->rule('lemon_squeezy_variant_id'))
                ->helpInfo('This is the Lemon Squeezy Variant ID and not any other variant code. Please check your Lemon Squeezy Store information')
                ->hideFromIndex(),

            Currency::make('LS price override', 'lemon_squeezy_price_override')
                ->rules($this->model()->rule('lemon_squeezy_price_override'))
                ->helpInfo('In case you want to override the price that you have configured for this variant id in Lemon Squeezy'),

            KeyValue::make('LS data', 'lemon_squeezy_data'),

            Panel::make('Timestamps', $this->timestamps($request)),

            EdBelongsTo::make('Course', 'course', Course::class),
        ];
    }
}
