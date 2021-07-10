<?php

namespace Eduka\Nova\Resources;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eduka\Abstracts\EdukaResource;
use Eduka\Cube\Models\Course as CourseModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
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
        'name'
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
            ID::make()
              ->onlyOnDetail(),

            Text::make('Name', function () {
                return env('APP_NAME');
            }),

            Boolean::make('Is Active?', 'is_active'),

            KeyValue::make('Meta')
                    ->keyLabel('Tag')
                    ->valueLabel('Content')
                    ->disableAddingRows()
                    ->disableDeletingRows()
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
