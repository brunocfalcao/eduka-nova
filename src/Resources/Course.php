<?php

namespace Eduka\Nova\Resources;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eduka\Abstracts\EdukaResource;
use Eduka\Cube\Models\Course as CourseModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;

class Course extends EdukaResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = CourseModel::class;

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
     * A Course can never be created twice.
     */
    public static function authorizedToCreate(Request $request)
    {
        return CourseModel::all()->count() == 0;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()
              ->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            KeyValue::make('Meta')
                    ->keyLabel('Tag')
                    ->valueLabel('Value')
                    ->actionText('Add Meta Tag'),

            Images::make('Logo')
                  ->rules('required')
                  ->conversionOnIndexView('thumb'),
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
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
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
