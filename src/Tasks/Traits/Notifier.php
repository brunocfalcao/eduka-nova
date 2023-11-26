<?php

namespace Eduka\Nova\Tasks\Traits;

use Eduka\Nova\Tasks\NotifyAdminTask;
use Exception;

trait Notifier
{
    private function notifyVideoNotFound(NotifyAdminTask $notifier, array $notificationRecipients, int $videoId)
    {
        $message = sprintf('Could not find video with id %s ', $videoId);

        $notifier->notify($notificationRecipients, 'error', $message);
    }

    private function notifyVideoStorageNotFound(NotifyAdminTask $notifier, array $notificationRecipients, int $storageId)
    {
        $message = sprintf('Could not find video storage with id %s ', $storageId);

        $notifier->notify($notificationRecipients, 'error', $message);
    }

    private function notifyVideoStorageVariantNotFound(NotifyAdminTask $notifier, array $notificationRecipients, int $storageId)
    {
        $message = sprintf('Could not find variant from video storage with id %s ', $storageId);

        $notifier->notify($notificationRecipients, 'error', $message);
    }

    private function notifyVideoUploadedSuccessfully(NotifyAdminTask $notifier, array $notificationRecipients, string $name, string $driver)
    {
        $notifier->notify($notificationRecipients, 'info', sprintf("Video file for '%s' has been uploaded to %s.", $name, $driver));
    }

    private function notifyFileDoesNotExist(NotifyAdminTask $notifier, array $notificationRecipients, string $videoPath, int $videoId)
    {
        $message = sprintf("Tried to upload non existant video. video path='%s' video id='%s'", $videoPath, $videoId);

        $notifier->notify($notificationRecipients, 'error', $message);
    }

    private function notifyException(NotifyAdminTask $notifier, array $notificationRecipients, Exception $e)
    {
        $notifier->notify($notificationRecipients, 'error', $e->getMessage());
    }
}
