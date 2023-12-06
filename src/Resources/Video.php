<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Actions\UploadVideo;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdTextarea;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Video extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Video::class;

    public static $title = 'name';

    public static $search = [
        'name',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        // Return videos only created by this user, that is the course admin.
        return $query->where('created_by', '=', $request->user()->id);
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Name'),

            Text::make('Vimeo Id', 'vimeo_id')
                ->hideWhenCreating(),

            Number::make('Duration')->displayUsing(function ($value) {
                if (! $value) {
                    return '';
                }

                if ($value < 60) {
                    return $value.' min';
                }

                $hours = (int) ($value / 60);
                $mins = $value % 60;

                return sprintf('%s hour %s mins', $hours, $mins);
            }),

            Boolean::make('Is Visible'),
            Boolean::make('Is Active'),
            Boolean::make('Is Free'),

            Panel::make('Metadata & Social', [
                Text::make('Title', 'meta_title')
                    ->rules('nullable', 'max:250')
                    ->hideFromIndex(),

                EdTextarea::make('Description', 'meta_description')
                    ->hideFromIndex()
                    ->rules('nullable', 'max:250'),

                Text::make('Canonical URL', 'meta_canonical_url')
                    ->hideFromIndex()
                    ->rules('nullable', 'max:250', 'url'),
            ]),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [
            new UploadVideo(),
        ];
    }
}
