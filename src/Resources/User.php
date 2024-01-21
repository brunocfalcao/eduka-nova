<?php

namespace Eduka\Nova\Resources;

use Eduka\Cube\Models\User as UserModel;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdID;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
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

    public static function indexQuery(NovaRequest $request, $query)
    {
        $courses = $request->user()->courses;

        $query->select('users.*')
            ->distinct()
            ->join('user_variant', 'users.id', 'user_variant.user_id')
            ->join('variants', 'user_variant.variant_id', 'variants.id')
            ->join('courses', 'variants.course_id', 'courses.id')
            ->whereIn('courses.id', $courses->pluck('id'));

        return $query;
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Gravatar::make()->maxWidth(50),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
