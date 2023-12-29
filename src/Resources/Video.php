<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
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
        return $query->where('created_by', '=', $request->user()->id);
    }

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Name'),

            Canonical::make(),

            Text::make('Vimeo Id', 'vimeo_id')
                ->hideWhenCreating(),

            Number::make('Duration (in secs)', 'duration')->displayUsing(function ($value) {
                if ($value <= 0) {
                    return '0 seconds';
                }

                $hours = intdiv($value, 3600);
                $minutes = intdiv($value % 3600, 60);
                $seconds = $value % 60;

                $pluralize = fn ($num, $word) => $num.' '.$word.($num === 1 ? '' : 's');
                $timeComponents = [];

                if ($hours > 0) {
                    $timeComponents[] = $pluralize($hours, 'hour');
                }
                if ($minutes > 0 || $hours > 0) {
                    $timeComponents[] = $pluralize($minutes, 'minute');
                }
                if ($seconds > 0 || $minutes > 0 || $hours > 0) {
                    $timeComponents[] = $pluralize($seconds, 'second');
                }

                return implode(' ', $timeComponents);
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
            ]),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [
            UploadVideo::make()->sole(),
        ];
    }
}
