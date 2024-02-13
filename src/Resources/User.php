<?php

namespace Eduka\Nova\Resources;

use Eduka\Cube\Models\User as UserModel;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Illuminate\Support\Carbon;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class User extends EdukaResource
{
    public static $model = UserModel::class;

    public static $title = 'name';

    public static $search = [
        'name', 'email',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Name')
                ->rules($this->model()->rule('name')),

            Text::make('Email')
                ->rules($this->model()->rule('email')),

            Boolean::make('Is course admin?', 'course_id_as_admin')
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $this->course_id_as_admin;
                }),

            EdBelongsTo::make('Course as admin', 'courseAsAdmin', Course::class)
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $this->course_id_as_admin;
                }),

            DateTime::make('Last logged in at', 'last_logged_in_at')
                ->readonly()
                ->displayUsing(function ($value) {
                    $timezone = config('app.timezone');

                    if ($value) {
                        return (new Carbon($value))->timezone($timezone)
                            ->format('F d, Y H:i');
                    }
                }),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
