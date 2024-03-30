<?php

namespace Eduka\Nova\Resources;

use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Email;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Filters\ByCourse;
use Laravel\Nova\Http\Requests\NovaRequest;
use Eduka\Nova\Resources\Fields\EdBelongsTo;

class Subscriber extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Subscriber::class;

    public static $title = 'email';

    public static $search = ['email'];

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Email::make('Email')
                 ->helpInfo('Subscriber email')
                 ->rules($this->model()->rule('email')),

            EdBelongsTo::make('Course', 'course', Course::class)
                 ->helpInfo('Related course')
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
