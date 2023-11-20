<?php

namespace Eduka\Nova\Tasks;

use Eduka\Cube\Models\VideoStorage;
use Eduka\Nova\Services\Backblaze\BackblazeClient;
use Eduka\Nova\Tasks\Traits\Notifier;
use Exception;
use Illuminate\Support\Facades\Log;

class HandleBackblazeUploadTask
{
    use Notifier;

    public function handle(int $storageId, array $notificationRecipients, string $bucket)
    {
        Log::info('bucket_info', [
            'name' => $bucket,
        ]);

        $notifier = new NotifyAdminTask;
        $videoStorage = VideoStorage::find($storageId);

        if (! $videoStorage) {
            $this->notifyVideoStorageNotFound($notifier, $notificationRecipients, $storageId);

            return;
        }

        try {
            (new BackblazeClient())->uploadTo($videoStorage->path_on_disk, $bucket, $videoStorage->video->name);
            (new HandlePostBackblazeUploadTask())->handle($videoStorage, 'could_not_parse_response_string');

            $this->notifyVideoUploadedSuccessfully($notifier, $notificationRecipients, $videoStorage->video->name, 'backblaze');
        } catch (Exception $e) {
            $this->notifyException($notifier, $notificationRecipients, $e);
        }
    }
}
