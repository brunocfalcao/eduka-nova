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

class UploadEpisode extends Action
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models)
    {
        try {
            /**
             * We only upload the episode to the server. Then the "updated"
             * model observer method will trigger the necessary jobs to
             * upload it to Youtube (if free), and Vimeo.
             */

            // Context the selected episode instance.
            $episodeModel = $models->first();

            // Context the selected episode file.
            $episodeFile = $fields->episode;

            // Upload the episode to the eduka web server.
            $path = Storage::putFile('episodes', $fields->episode);

            /**
             * Path will be smth like episodes/<filename>.mp4. Then we can fetch
             * the full url using storage_path('app/$path'). Update episode.
             */
            $episodeModel->update([
                'temp_filename_path' => $path,
            ]);

            return Action::message('Episode uploaded to web server. Actions for further uploads to episode platforms are triggered');
        } catch (\Exception $e) {
            return Action::message($e->getMessage());
        }
    }

    public function fields(NovaRequest $request)
    {
        return [
            File::make('Episode', 'episode')
                ->rules('required', 'file', 'mimetypes:video/avi,video/mp4,video/mpeg,video/quicktime', 'max:20480'),
        ];
    }
}
