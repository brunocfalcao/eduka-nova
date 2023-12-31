<?php

namespace Eduka\Nova\Resources\Actions;

use Eduka\Cube\Models\VideoStorage;
use Eduka\Nova\Services\UploadToBackblazeJob;
use Eduka\Nova\Services\UploadToVimeoJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
        try {
            $video = $models->first();

            $videoStorage = VideoStorage::firstWhere('video_id', $video->id);

            $path = Storage::putFile('videos', $fields->video);

            if ($videoStorage) {
                Storage::delete($videoStorage->path_on_disk);
                $videoStorage->update([
                    'path_on_disk' => $path,
                ]);
            } else {
                $videoStorage = VideoStorage::create([
                    'video_id' => $video->id,
                    'path_on_disk' => $path,
                ]);
            }

            // This is an admin user. So it belongs to 1 course only.
            $course = Auth::user()->courses->first();

            UploadToVimeoJob::dispatch($video->id, $course->id, Auth::id());
            //UploadToBackblazeJob::dispatch($videoStorage->id, $course->id);

            return Action::message('Video upload to Youtube/Vimeo/Backblaze started in the background. You will be notified when it finishes');
        } catch (\Exception $e) {
            return Action::message($e->getMessage());
        }
    }

    public function fields(NovaRequest $request)
    {
        return [
            File::make('Video')
                ->rules('required', 'file', 'mimetypes:video/avi,video/mp4,video/mpeg,video/quicktime', 'max:20480'),
        ];
    }
}
