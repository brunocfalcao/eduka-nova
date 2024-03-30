<?php

namespace Eduka\Nova\Resources;

use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Filters\ByCourse;
use Eduka\Nova\Resources\Fields\EdTextarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Eduka\Nova\Resources\Fields\EdBelongsTo;

class Series extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Series::class;

    public static $title = 'name';

    public static $search = ['name'];

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Name')
                ->rules($this->model()->rule('name')),

            EdTextarea::make('description')
                ->rules($this->model()->rule('description'))
                ->hideFromIndex(),

            EdBelongsTo::make('Course', 'course', Course::class)
                       ->rules($this->model()->rule('course')),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new ByCourse(),
        ];
    }
}
