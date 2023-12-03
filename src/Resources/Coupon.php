<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Coupon extends Resource
{
    public static $model = \Eduka\Cube\Models\Coupon::class;

    public static $title = 'description';

    public static $search = [
        'code',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Code')
                ->rules('required'),

            Textarea::make('Description')
                    ->rules('required'),

            Number::make('Discount amount', 'discount_amount')
                  ->rules('numeric'),

            Number::make('Discount percentage', 'discount_percentage')
                  ->rules('numeric'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    /**
     * Handle any post-validation processing.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected static function afterValidation(NovaRequest $request, $validator)
    {
        if ((bool) $request->is_flat_discount === false && ((float) $request->discount_amount > 100)) {
            $validator->errors()->add('discount_amount', 'Creating a discount coupon with more than 100%.');
        }
    }
}
