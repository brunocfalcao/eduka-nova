<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Variant extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Variant::class;

    public static $title = 'canonical';

    public static $search = [
        'canonical',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Hidden::make('Uuid')->default(function ($request) {
                return Str::orderedUuid();
            }),

            Panel::make('Basic info', [
                Canonical::make(),

                Boolean::make('Default', 'is_default')
                    ->rules('boolean'),

                Textarea::make('Description', 'description')
                    ->rules('required', 'max:250'),
            ]),

            Panel::make('Lemon Squeezy', [
                Text::make('Variant ID', 'lemon_squeezy_variant_id')
                    ->rules('required', 'string')
                    ->hideFromIndex(),

                Number::make('Price override', 'lemon_squeezy_price_override')
                    ->rules('nullable', 'numeric'),
            ]),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
