<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Order extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Order::class;

    public static $title = 'id';

    public static $search = [
        'id', 'response_ body',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

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

            DateTime::make('Refunded', 'refunded_at')
                    ->hideFromIndex(),

            KeyValue::make('API Response', 'response_body')
                    ->json()
                    ->onlyOnDetail(),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
