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

        if (count($video->variants) === 0) {
            return Action::danger('Video does not have any relation with variants.');
        }

        $videoStorage = VideoStorage::where('video_id', $video->id)->first();

        $path = Storage::putFile('videos', $fields->video);

        if (! $videoStorage) {
            $videoStorage = VideoStorage::create([
                'video_id' => $video->id,
                'path_on_disk' => $path,
            ]);
        }

        $courseId = $video->variants->first()->course_id;

        UploadToVimeoJob::dispatch($video->id, $courseId);
        UploadToBackblazeJob::dispatch($videoStorage->id, $courseId);

        return Action::message('Video upload started in the background.');
    }

    public function fields(NovaRequest $request)
    {
        return [
            File::make('Video')
                ->rules('required', 'file', 'mimetypes:video/avi,video/mp4,video/mpeg,video/quicktime', 'max:20480'),
        ];
    }
}
