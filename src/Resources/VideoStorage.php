<?php

namespace Eduka\Nova\Resources;

use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class VideoStorage extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\VideoStorage::class;

    public static $title = 'video_id';

    public function fields(NovaRequest $request)
    {
        return [
            EdID::make(),

            EdBelongsTo::make('Video', 'video', Video::class),

            Text::make('Vimeo ID', 'vimeo_id'),

            Text::make('Backblaze ID', 'backblaze_id'),

            Text::make('Filename path', 'path_on_disk'),

            Panel::make('Timestamps', $this->timestamps($request)),
        ];
    }
}
