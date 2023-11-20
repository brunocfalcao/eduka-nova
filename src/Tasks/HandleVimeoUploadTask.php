<?php

namespace Eduka\Nova\Tasks;

use Eduka\Cube\Models\Video;
use Eduka\Nova\Services\Vimeo\VimeoClient;
use Eduka\Nova\Tasks\Traits\Notifier;
use Exception;
use Illuminate\Support\Facades\Storage;

class HandleVimeoUploadTask
{
    use Notifier;

    public function handle(int $videoId, array $notificationRecipients = [])
    {
        $notifier = new NotifyAdminTask;

        $video = Video::select('id', 'name', 'meta_description', 'vimeo_id')
            ->with('videoStorage')
            ->where('id', $videoId)
            ->first();

        if (! $video) {
            $this->notifyVideoNotFound($notifier, $notificationRecipients, $videoId);

            return;
        }

        $videoPath = $video->videoStorage->path_on_disk;

        if (! Storage::exists($videoPath)) {
            $this->notifyFileDoesNotExist($notifier, $notificationRecipients, $videoPath, $videoId);

            return;
        }

        $path = Storage::path($videoPath);

        try {
            $vimeoUrl = (new VimeoClient)->upload($path, $video->vimeoMetadata());

            $vimeoId = preg_replace('/[^0-9]/', '', $vimeoUrl);

            (new HandlePostVimeoUploadTask)->handle($video, $vimeoId);
        } catch (Exception $e) {

            $this->notifyException($notifier, $notificationRecipients, $e);

            throw $e;
        }

        $this->notifyVideoUploadedSuccessfully($notifier, $notificationRecipients, $video->name, 'vimeo');
    }
}
