<?php

namespace Eduka\Nova\Tasks;

use Eduka\Cube\Actions\Video\GetVariantFromVideo;
use Eduka\Cube\Actions\Video\UpdateVariantBucket;
use Eduka\Cube\Actions\VideoStorage\FindVideoStorageForBackblazeUpload;
use Eduka\Cube\Actions\VideoStorage\UpdateBackblazeId;
use Eduka\Nova\Tasks\Traits\Notifier;
use Eduka\Services\External\Backblaze\BackblazeClient;
use Exception;

class HandleBackblazeUploadTask
{
    use Notifier;

    public function handle(int $storageId, array $notificationRecipients)
    {
        $notifier = new NotifyAdminTask;

        // @todo select only necessary columns
        // what we need: video, variant & course
        $videoStorage = FindVideoStorageForBackblazeUpload::find($storageId);

        if (! $videoStorage) {
            $this->notifyVideoStorageNotFound($notifier, $notificationRecipients, $storageId);

            return;
        }

        $variant = GetVariantFromVideo::get($videoStorage->video);

        if (! $variant) {
            $this->notifyVideoStorageVariantNotFound($notifier, $notificationRecipients, $storageId);

            return;
        }

        $bbClient = new BackblazeClient;

        $existingBucket = $variant->getBucketName();

        // New bucket if a bucket with the name $bucketName does not exists
        $newBucketToBe = $variant->createBucketNameUsing();

        try {
            // check if the bucket exists or not
            // if it doesn't,
            // create a new one
            $bucket = $bbClient->ensureBucketExists($existingBucket, $newBucketToBe);

            if($existingBucket !== $bucket) {
                // a new bucket was created, update database
                UpdateVariantBucket::update($variant, $bucket);
            }

            $bbClient->uploadTo($videoStorage->path_on_disk, $bucket, $videoStorage->video->name);

            UpdateBackblazeId::handle($videoStorage,'video uploaded; attention: could not parse response');

            $this->notifyVideoUploadedSuccessfully($notifier, $notificationRecipients, $videoStorage->video->name, 'backblaze');

        } catch (Exception $e) {
            $this->notifyException($notifier, $notificationRecipients, $e);
        }
    }
}
