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
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Chapter extends EdukaResource
{
    use DefaultAscPKSorting;

    public static $model = \Eduka\Cube\Models\Chapter::class;

    public static $title = 'name';

    public static $search = [
        'name', 'description',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            // Confirmed.
            EdID::make(),

            // Confirmed.
            Text::make('Name')
                ->rules($this->model()->rule('name')),

            // Confirmed.
            EdTextarea::make('description')
                ->rules($this->model()->rule('description')),

            // Confirmed.
            Number::make('Index'),
            // No need to apply the index rules, since it's not required.
            //->rules($this->model()->rule('index')),

            // Confirmed.
            KeyValue::make('meta_names')
                ->rules($this->model()->rule('meta_names')),

            // Confirmed.
            KeyValue::make('meta_properties')
                ->rules($this->model()->rule('meta_properties')),

            // Confirmed.
            EdImage::make('SEO Image', 'filename')
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
            BelongsToMany::make('Related Variants', 'variants', Variant::class),

            // Confirmed.
            HasMany::make('Episodes', 'episodes', Episode::class),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new ByCourse(),
        ];
    }
}
