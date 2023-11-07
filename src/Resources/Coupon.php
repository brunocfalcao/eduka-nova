<?php

namespace Eduka\Nova\Resources;

use Eduka\Cube\Util\Country;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Coupon extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Coupon>
     */
    public static $model = \Eduka\Cube\Models\Coupon::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'code';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'code',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->orderBy('country_iso_code');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Code')
                ->rules('required', 'alpha_dash')
                ->sortable(),

            Number::make('Discount', 'discount_amount')
                ->displayUsing(function ($value, $coupon) {
                    return sprintf('%s %s', $coupon->discount_amount, $coupon->is_flat_discount ? config('eduka.currency') : '%');
                })
                ->rules('required', 'numeric'),

            Boolean::make('Flat discount', 'is_flat_discount')
                ->rules('boolean')
                ->hideFromIndex(),

            Select::make('Country', 'country_iso_code')
                ->options(Country::list()) // the country code should be UPPER case
                ->rules('nullable', 'in:' . implode(',', array_keys(Country::list())))
                ->sortable(),

            Text::make('Ref', 'remote_reference_id')
                ->hideFromIndex()
                ->placeholder('Should be created automatically by the application when a new coupon is created on 3rd party payment provider')
                ->help("Ref ('remote_reference_id' in the database is the id is provided by the 3rd party payment provider. If the coupon was already created in 3rd party (eg: lemon squeezy)"),

            BelongsTo::make('Course', 'course', Course::class),
        ];
    }

    /**
     * Handle any post-validation processing.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected static function afterValidation(NovaRequest $request, $validator)
    {
        if ((bool) $request->is_flat_discount === false && ((float) $request->discount_amount > 100 )) {
            $validator->errors()->add('discount_amount', 'Creating a discount coupon with more than 100%.');
        }
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
