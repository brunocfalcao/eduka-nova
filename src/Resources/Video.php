<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Eduka\Cube\Models\Video as VideoModel;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Actions\UploadVideo;
use Eduka\Nova\Resources\Fields\EdHasMany;
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

    public function title()
    {
        $video = VideoModel::with('chapter')->find($this->id);

        return $this->name.($video->chapter ? " ({$video->chapter->name})" : '');
    }

    public static $search = [
        'name', 'description',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            Text::make('Name'),

            Canonical::make()
                ->readonly()
                ->onlyOnDetail(),

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

            // Confirmed.
            EdHasMany::make('Links', 'links', Link::class),

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
