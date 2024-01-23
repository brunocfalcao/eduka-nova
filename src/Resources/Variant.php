<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdUUID;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
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

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            EdUUID::make('UUid')
                ->readonly(),

            Panel::make('Basic info', [
                Canonical::make(),

                Boolean::make('Default', 'is_default'),

                Text::make('Description', 'description'),
            ]),

            Panel::make('Lemon Squeezy', [
                Text::make('Variant ID', 'lemonsqueezy_variant_id')
                    ->hideFromIndex(),

                Number::make('Price override', 'lemonsqueezy_price_override'),
            ]),

            Panel::make('Timestamps', $this->timestamps($request)),

            BelongsToMany::make('Chapters', 'chapters', Chapter::class),
        ];
    }
}
