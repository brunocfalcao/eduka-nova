<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Traits\DefaultAscPKSorting;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdImage;
use Eduka\Nova\Resources\Fields\EdTextarea;
use Eduka\Nova\Resources\Filters\ByCourse;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Chapter extends EdukaResource
{
    use DefaultAscPKSorting;

    public static $model = \Eduka\Cube\Models\Chapter::class;

    public static $title = 'name';

    public static $search = ['name', 'description'];

    public function fields(NovaRequest $request)
    {
        return [
            // Confirmed.
            EdID::make(),

            // Confirmed.
            Text::make('Name', 'name')
                ->helpInfo('The chapter name')
                ->rules($this->model()->rule('name')),

            // Confirmed.
            EdTextarea::make('description', 'description')
                ->helpInfo('Elaborated chapter description')
                ->rules($this->model()->rule('description')),

            // Confirmed.
            Number::make('Index', 'index')
                ->helpInfo('The chapter index related to the course'),

            // Confirmed.
            EdImage::make('SEO Image', 'filename')
                ->hideFromIndex()
                ->helpInfo('The image for social integration purposes (resolution: 1200x600)')
                ->rules($this->model()->rule('filename')),

            // Confirmed.
            Text::make('Vimeo URI', 'vimeo_uri')
                ->readonly()
                ->exceptOnForms()
                ->hideFromIndex(),

            // Confirmed.
            Number::make('Vimeo Folder Id', 'vimeo_folder_id')
                ->readonly()
                ->exceptOnForms()
                ->hideFromIndex(),

            // Confirmed.
            EdBelongsTo::make('Course', 'course', Course::class),

            // Confirmed.
            Panel::make('Timestamps', $this->timestamps($request)),

            // Confirmed.
            BelongsToMany::make('Variants', 'variants', Variant::class),

            // Confirmed.
            HasMany::make('Episodes', 'episodes', Episode::class),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new ByCourse,
        ];
    }
}
