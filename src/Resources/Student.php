<?php

namespace Eduka\Nova\Resources;

use Eduka\Cube\Models\Student as StudentModel;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Filters\ByStudentCourse;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Student extends EdukaResource
{
    public static $model = StudentModel::class;

    public static $title = 'name';

    public static $search = ['name', 'email'];

    public function fields(NovaRequest $request)
    {
        return [
            // Confirmed.
            EdID::make(),

            // Confirmed.
            Text::make('Name')
                ->rules($this->model()->rule('name')),

            // Confirmed.
            Text::make('Email')
                ->rules($this->model()->rule('email')),

            // Confirmed.
            DateTime::make('Previous logged in at', 'previous_logged_in_at')
                ->readonly()
                ->displayUsing(function ($value) {
                    return human_date($value);
                }),

            // Confirmed.
            DateTime::make('Last logged in at', 'last_logged_in_at')
                ->readonly()
                ->displayUsing(function ($value) {
                    return human_date($value);
                }),

            // Confirmed.
            BelongsToMany::make('Seen Episodes', 'episodesThatWereSeen', Episode::class),

            // Confirmed.
            BelongsToMany::make('Bookmarked Episodes', 'episodesThatWereBookmarked', Episode::class),

            // Confirmed.
            BelongsToMany::make('Variants', 'variants', Variant::class),

            // Confirmed.
            BelongsToMany::make('Courses', 'courses', Course::class),

            // Confirmed.
            HasMany::make('Orders', 'orders', Order::class),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }

    public function filters(Request $request)
    {
        return [
            ByStudentCourse::make(),
        ];
    }
}
