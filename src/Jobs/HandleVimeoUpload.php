<?php

namespace Eduka\Nova\Jobs;

use Eduka\Cube\Models\Video;
use Eduka\Nova\Actions\UploadToVimeo;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Notifications\NovaNotification;
use User;

class HandleVimeoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private int $videoId, private string $videoPath)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!Storage::disk('local')->exists($this->videoPath)) {
            throw new \Exception(sprintf("tried to upload non existant video. video path='%s' video id='%s'", $this->videoPath, $this->videoId));
        }

        $video = Video::select('id', 'name', 'meta_description', 'vimeo_id')->where('id', $this->videoId)->first();

        if (!$video) {
            throw new \Exception(sprintf("could not find video with id %s" , $this->videoId));
        }

        $path = Storage::disk('local')->path($this->videoPath);

        $vimeoUrl = null;

        try {
            $vimeoUrl = (new UploadToVimeo)->upload($path, $video->vimeoMetadata());
        } catch (Exception $e) {
            throw $e;
        }

        $video->update(['vimeo_id' => $vimeoUrl]);

        $video->videoStorage->update(['vimeo_id' => $vimeoUrl]);

        $user = User::where('email', env('ADMIN_EMAIL'))->first();

        $$user->notify(
            NovaNotification::make()
                ->message(sprintf("Video file for '%s' has been uploaded to vimeo.", $video->name))
                ->type('info')
        );
    }
}
