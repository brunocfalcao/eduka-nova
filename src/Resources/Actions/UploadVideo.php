<?php

namespace Eduka\Nova\Resources\Actions;

use Eduka\Cube\Models\VideoStorage;
use Eduka\Nova\Jobs\UploadToBackblazeJob;
use Eduka\Nova\Jobs\UploadToVimeoJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class UploadVideo extends Action
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() > 1) {
            return Action::danger('Please run this on only one video resource.');
        }

        $path = Storage::putFile('videos', $fields->video);

        $video = $models->first();

        $videoStorage = VideoStorage::where('video_id', $video->id)->first();

        if (! $videoStorage) {
            $videoStorage = VideoStorage::create([
                'video_id' => $video->id,
                'path_on_disk' => $path,
            ]);
        }

        UploadToVimeoJob::dispatch($video->id);
        UploadToBackblazeJob::dispatch($videoStorage->id);

        return Action::message('Video upload started . ', $path);
    }

    public function fields(NovaRequest $request)
    {
        return [
            File::make('Video'),

            Select::make('Course'),
        ];
    }
}
