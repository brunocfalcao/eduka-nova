<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdTextarea;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

/**
 * DONE
 */
class Chapter extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Chapter::class;

    public static $title = 'name';

    public static $search = [
        'name',
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

            Text::make('Name')
                ->required(),

            EdTextarea::make('description'),

            EdBelongsTo::make('Course', 'course', Course::class)
                       ->required(),

            Panel::make('Timestamps', $this->timestamps($request)),

            BelongsToMany::make('Variants', 'variants', Variant::class),
        ];
    }
}
