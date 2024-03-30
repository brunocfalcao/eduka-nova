<?php

namespace Eduka\Nova\Resources;

use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Filters\ByCourse;
use Laravel\Nova\Http\Requests\NovaRequest;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdBelongsToMany;

class Tag extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Tag::class;

    public static $title = 'name';

    public static $search = ['name'];

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Name')
                ->helpInfo('Tag name. Obviously')
                ->rules($this->model()->rule('name')),

            EdBelongsTo::make('Course', 'course', Course::class)
                       ->helpInfo('Related course')
                       ->rules($this->model()->rule('course')),

            EdBelongsToMany::make('Episodes', 'episodes', Episode::class),

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
