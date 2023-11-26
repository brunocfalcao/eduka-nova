<?php

namespace Eduka\Nova\Resources\Actions;

use Eduka\Cube\Models\VideoStorage;
use Eduka\Nova\Jobs\UploadToBackblazeJob;
use Eduka\Nova\Jobs\UploadToVimeoJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;

class UploadVideo extends Action
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() > 1) {
            return Action::danger('Please run this on only one video resource.');
        }

        $video = $models->first();

        if ($video->hasVimeoId() && $fields->override === false) {
            return Action::danger('A video file on vimeo already exists, select override to upload and replace');
        }

        $videoStorage = VideoStorage::where('video_id', $video->id)->first();

        $path = Storage::putFile('videos', $fields->video);

        if (!$videoStorage) {
            $videoStorage = VideoStorage::create([
                'video_id' => $video->id,
                'path_on_disk' => $path,
            ]);
        }

        UploadToVimeoJob::dispatch($video->id);
        UploadToBackblazeJob::dispatch($videoStorage->id);

        return Action::message('Video upload started in the background.');
    }

    public function fields(NovaRequest $request)
    {
        return [
            File::make('Video')->rules('required', 'file', 'mimes:video/mp4,video/avi,video/wmv,video/quicktime', 'max:20480'),

            Boolean::make('Override existing video?', 'override'),
        ];
    }
}
