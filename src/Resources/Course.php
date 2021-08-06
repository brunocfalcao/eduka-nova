<?php

namespace Eduka\Nova\Resources;

use Eduka\Abstracts\EdukaResource;
use Eduka\Cube\Models\Course as CourseModel;
use Eduka\Nova\Actions\CourseLaunch;
use Eduka\Nova\Services\MetaImageLogoAttachment;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
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
        'name',
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

            Text::make('Name', 'name')
                ->rules('required'),

            Text::make('Canonical', 'canonical')
                ->help('The unique canonical name. Used in some parts of the framework to directly query this course by the hardcoded canonical')
                ->creationRules('required', 'unique:courses,canonical')
                ->updateRules('required', 'unique:courses,canonical,{{resourceId}}'),

            Text::make('Postmark Token', 'postmark_token')
                ->help('The postmark API token')
                ->rules('required')
                ->hideFromIndex(),

            Text::make('Config name', 'config_name')
                ->help('The php config filename when you publish your course assets config file. E.g.: "nova-advanced-ui"')
                ->rules('required')
                ->hideFromIndex(),

            Text::make('Email name', 'from_name')
                ->help('The email name alias used to send course emails')
                ->rules('required'),

            Text::make('Email address', 'from_email')
                ->help('The email address used to send course emails')
                ->rules('required'),

            Text::make('Provider namespace', 'provider_namespace')
                ->help('E.g.: MasteringNova\MasteringNovaServiceProvider')
                ->rules('required')
                ->hideFromIndex(),

            Boolean::make('Is Active?', 'is_active'),

            KeyValue::make('Meta Tags', 'meta_tags')
                    ->help('Remaining meta tags are non-editable and system generated')
                    ->keyLabel('Tag')
                    ->valueLabel('Content')
                    ->disableAddingRows()
                    ->disableDeletingRows()
                    ->hideWhenCreating(),

            Image::make('Meta Image', 'meta_image')
                 ->store(new MetaImageLogoAttachment)
                 ->help('Min resolution 1600px by 800px, and aspect ratio of 2:1')
                 ->rules('dimensions:min_width=1600,min_height=800,ratio=2:1')
                 ->prunable()
                 ->nullable(),

            Date::make('Launched at', 'launched_at'),

            HasMany::make('Domains', 'domains', Domain::class),
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
        return [

            (new CourseLaunch())->canSee(function ($request) {
                if ($this->model()->launched_at == null) {
                    return true;
                }
            })->showOnTableRow(),

        ];
    }
}
