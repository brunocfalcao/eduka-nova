<?php

namespace Eduka\Nova\Actions;

use Exception;
use Eduka\Cube\Models\Video;
use Illuminate\Support\Facades\Storage;

class HandleVimeoUploadTask
{
    private function notifyVideoNotFound(NotifyAdmin $notifier, array $notificationRecipients, int $videoId)
    {
        $message = sprintf('Could not find video with id %s ', $videoId);

        $notifier->notify($notificationRecipients, 'error', $message);
    }

    private function notifyVideoUploadedSuccessfully(NotifyAdmin $notifier,array $notificationRecipients, string $name)
    {
        $notifier->notify($notificationRecipients, 'info', sprintf("Video file for '%s' has been uploaded to vimeo.", $name));
    }

    private function notifyFileDoesNotExist(NotifyAdmin $notifier,array $notificationRecipients, string $videoPath , int $videoId)
    {
        $message = sprintf("Tried to upload non existant video. video path='%s' video id='%s'", $videoPath, $videoId);

        $notifier->notify($notificationRecipients, 'error', $message);
    }

    private function notifyException(NotifyAdmin $notifier,array $notificationRecipients, Exception $e)
    {
        $notifier->notify($notificationRecipients, 'error', $e->getMessage());
    }


    public function handle(int $videoId, array $notificationRecipients = [])
    {
        $notifier = new NotifyAdmin;

        $video = Video::select('id', 'name', 'meta_description', 'vimeo_id')
            ->with('storage')
            ->where('id', $videoId)
            ->first();

        if (! $video) {
            $this->notifyVideoNotFound($notifier, $notificationRecipients, $videoId);
            return;
        }

        $videoPath = $video->storage->path_on_disk;

        if (! Storage::disk('local')->exists($videoPath)) {
            $this->notifyFileDoesNotExist($notifier, $notificationRecipients, $videoPath, $videoId);
            return;
        }

        $path = Storage::disk('local')->path($videoPath);

        try {
            $vimeoUrl = (new VimeoHandler)->upload($path, $video->vimeoMetadata());

            $vimeoId = preg_replace('/[^0-9]/', '', $vimeoUrl);

            (new HandlePostVimeoUploadTask)->handle($video, $vimeoId);

        } catch (Exception $e) {

            $this->notifyException($notifier, $notificationRecipients, $e);

            throw $e;
        }

        $this->notifyVideoUploadedSuccessfully($notifier, $notificationRecipients, $video->name);
    }
}
