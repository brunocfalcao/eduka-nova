<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdTextarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Coupon extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Coupon::class;

    public static $title = 'description';

    public static $search = [
        'code', 'description',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->whereIn(
            'course_id',
            $request->user()
                    ->courses
                    ->pluck('id')
        );
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Code'),

            EdTextarea::make('Description'),

            Number::make('Discount amount', 'discount_amount'),

            Number::make('Discount percentage', 'discount_percentage'),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
