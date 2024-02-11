<?php

namespace Eduka\Nova\Resources;

use Eduka\Cube\Models\Order as OrderModel;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Order extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Order::class;

    public function title()
    {
        $order = OrderModel::with('user')->find($this->id);

        return 'Order from '.$this->user->name.' ('.$this->order_number.')';
    }

    public static $search = [
        'response_ body', 'event_name', 'order_name',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where(
            'course_id',
            $request->user()
                    ->course_id_as_admin
        );
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            EdBelongsTo::make('Course', 'course', Course::class),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
