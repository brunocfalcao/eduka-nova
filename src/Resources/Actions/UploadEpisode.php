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
            $episodeModel = $models->first();
            $path = Storage::putFile('episodes', $fields->episode);
            $fullPath = storage_path('app/'.$path);
            $totalDuration = null; // Initialize duration as NULL

            try {
                // Use FFmpeg to get the duration of the video
                $command = 'ffmpeg -i "'.$fullPath.'" 2>&1 | findstr "Duration"';
                exec($command, $output);

                // Parse output to find duration
                if (preg_match('/Duration: (\d+):(\d+):(\d+)/', implode("\n", $output), $matches)) {
                    $hours = $matches[1];
                    $minutes = $matches[2];
                    $seconds = $matches[3];
                    // Calculate total duration in seconds
                    $totalDuration = $hours * 3600 + $minutes * 60 + $seconds;
                }
            } catch (\Exception $e) {
                $totalDuration = null;
            }

            $episodeModel->update([
                'temp_filename_path' => $path,
                'duration' => $totalDuration, // Update with either calculated duration or NULL
            ]);

            return Action::message('Episode uploaded and duration updated.');
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
