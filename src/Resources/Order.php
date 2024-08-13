<?php

namespace Eduka\Nova\Resources;

use Eduka\Cube\Models\Order as OrderModel;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Filters\ByCourse;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Order extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Order::class;

    public function title()
    {
        $order = OrderModel::with('student')->find($this->id);

        return 'Order from '.$this->student->name.' ('.$this->order_number.')';
    }

    public static $search = [
        'response_ body', 'event_name', 'order_name',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            EdBelongsTo::make('Course', 'course', Course::class),

            EdBelongsTo::make('Student', 'student', Student::class),

            EdBelongsTo::make('Variant', 'variant', Variant::class),

            Text::make('Provider'),

            Text::make('Country'),

            Text::make('Event name', 'event_name'),

            Text::make('Store ID', 'store_id'),

            Text::make('Customer ID', 'customer_id'),

            Text::make('Order Number', 'order_number'),

            Text::make('Student name', 'student_name'),

            Text::make('Student email', 'student_email'),

            Text::make('Subtotal USD', 'subtotal_usd'),

            Text::make('Discount total USD', 'discount_total_usd'),

            Text::make('Tax USD', 'tax_usd'),

            Text::make('Total USD', 'total_usd'),

            Text::make('Tax name', 'tax_name'),

            Text::make('Status', 'status'),

            Text::make('Refunded', 'refunded'),

            Text::make('Refunded At', 'refunded_at'),

            Text::make('Order ID', 'order_id'),

            Text::make('LS Product ID', 'lemon_squeezy_product_id'),

            Text::make('LS Variant ID', 'product_id'),

            Text::make('LS Product name', 'lemon_squeezy_product_name'),

            Text::make('LS Variant name', 'lemon_squeezy_variant_name'),

            Text::make('Price', 'price'),

            Text::make('Receipt', 'receipt'),

            KeyValue::make('Response body', 'response_body'),

            KeyValue::make('Custom data', 'custom_data'),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new ByCourse,
        ];
    }
}
