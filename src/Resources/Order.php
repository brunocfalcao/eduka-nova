<?php

namespace Eduka\Nova\Resources;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = \Eduka\Cube\Models\Order::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User', 'user', User::class),
            BelongsTo::make('Course', 'course', Course::class),

            Currency::make('Total', function () {
                return $this->total / 100;
            })->currency(config('eduka.currency')),

            Currency::make('Sub total', function () {
                return $this->subtotal / 100;
            })->currency(config('eduka.currency'))
                ->hideFromIndex(),

            Currency::make('Discount', function () {
                return $this->discount_total / 100;
            })->currency(config('eduka.currency'))
                ->hideFromIndex(),

            Currency::make('Tax', function () {
                return $this->tax / 100;
            })->currency(config('eduka.currency'))
                ->hideFromIndex(),

            DateTime::make('Datetime', 'created_at'),

            Boolean::make('Payment status', 'remote_reference_payment_status')
            ->resolveUsing(function ($remoteReferencePaymentStatus) {
                return $remoteReferencePaymentStatus == 'paid';
            })
            ->onlyOnIndex(),

            Text::make('Payment status', 'remote_reference_payment_status')
                ->capitalizeFirst()
                ->hideFromIndex(),

            DateTime::make('Refunded', 'refunded_at')->hideFromIndex(),

            Code::make('API Response', 'response_body')->json()->onlyOnDetail(),

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
}
