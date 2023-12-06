<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdTextarea;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Chapter extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Chapter::class;

    public static $title = 'name';

    public static $search = [
        'name',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        /**
         * Return all the chapters that are part of variants of this course.
         */
        $course = $request->user()->courses();

        $query->distinct()
              ->join('user_variant', 'users.id', 'user_variant.user_id')
              ->whereIn('user_variant.variant_id', $course->variants->pluck('id'))
              ->select('users.*');

        return $query;

        return $query;
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Name')
                ->rules('required'),

            EdTextarea::make('description'),

            Panel::make('Timestamps', $this->timestamps($request)),

            BelongsToMany::make('Variants', 'variants', Variant::class),
        ];
    }
}
