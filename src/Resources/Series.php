<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdTextarea;
use Eduka\Nova\Resources\Filters\ByCourse;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

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
            new ByCourse,
        ];
    }
}
