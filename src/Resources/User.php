<?php

namespace Eduka\Nova\Resources;

use Eduka\Abstracts\EdukaResource;
use Eduka\Nova\Filters\User\PreSubscribed;
use Eduka\Nova\Metrics\User\NewUsers;
use Eduka\Nova\Metrics\User\UsersPerDay;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;

class User extends EdukaResource
{
    public static $indexDefaultOrder = ['created_at' => 'desc'];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Eduka\Cube\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Boolean::make('Is admin?', 'is_admin')
                   ->help('If admin then it can log in into Nova, and use Horizon'),

            DateTime::make('Registered on', 'created_at')
                ->readonly()
                ->onlyOnDetail(),

            DateTime::make('Last update', 'updated_at')
                ->readonly()
                ->onlyOnDetail(),

            DateTime::make('Email verified at', 'email_verified_at')
                ->readonly()
                ->onlyOnDetail(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            Text::make('Remember Token', 'remember_token')
                ->readonly()
                ->onlyOnDetail(),

            BelongsTo::make('Subscriber', 'subscriber', Subscriber::class)
                     ->hideFromIndex()
                     ->readOnly(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            new NewUsers(),
            new UsersPerDay(),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new PreSubscribed(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
