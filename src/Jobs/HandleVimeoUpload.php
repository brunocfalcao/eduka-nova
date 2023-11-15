<?php

namespace Eduka\Nova\Jobs;

use Eduka\Cube\Models\Video;
use Eduka\Nova\Actions\HandlePostVimeoUploadTask;
use Eduka\Nova\Actions\NotifyAdmin;
use Eduka\Nova\Actions\UploadToVimeo;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

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
        $notifier = new NotifyAdmin;
        $notificationRecipients = [env('ADMIN_EMAIL')];

        if (!Storage::disk('local')->exists($this->videoPath)) {
            $message = sprintf("tried to upload non existant video. video path='%s' video id='%s'", $this->videoPath, $this->videoId);

            $notifier->notify($notificationRecipients, 'error', $message );

            throw new \Exception($message);
        }

        $video = Video::select('id', 'name', 'meta_description', 'vimeo_id')->where('id', $this->videoId)->first();

        if (!$video) {
            $message = sprintf("could not find video with id %s ", $this->videoId);

            $notifier->notify($notificationRecipients, 'error', $message );

            throw new \Exception($message);
        }

        $path = Storage::disk('local')->path($this->videoPath);

        try {
            $vimeoUrl = (new UploadToVimeo)->upload($path, $video->vimeoMetadata());

            $vimeoId = preg_replace('/[^0-9]/', '', $vimeoUrl);

            (new HandlePostVimeoUploadTask)->handle($video, $vimeoId, $this->videoPath);

        } catch (Exception $e) {
            $notifier->notify($notificationRecipients, 'error', $e->getMessage() );

            throw $e;
        }

        $notifier->notify($notificationRecipients, 'info', sprintf("Video file for '%s' has been uploaded to vimeo.", $video->name));
    }
}
