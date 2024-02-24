<?php

namespace Eduka\Nova\Resources\Actions;

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
        try {
            /**
             * We only upload the video to the server. Then the "updated"
             * model observer method will trigger the necessary jobs to
             * upload it to Youtube (if free), Backblaze and Vimeo.
             */

            // Context the selected video instance.
            $videoModel = $models->first();

            // Context the selected video file.
            $videoFile = $fields->video;

            // Upload the video to the eduka web server.
            $path = Storage::putFile('videos', $fields->video);

            /**
             * Path will be smth like videos/<filename>.mp4. Then we can fetch
             * the full url using storage_path('app/$path'). Update video.
             */
            $videoModel->update([
                'temp_filename_path' => $path,
            ]);

            return Action::message('Video uploaded to web server. Actions for further uploads to video platforms are triggered');
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
