<?php

namespace Eduka\Nova\Tasks;

use Eduka\Cube\Actions\Episode\UpdateBackblazeBucketName;
use Eduka\Cube\Actions\EpisodeStorage\FindEpisodeStorageForBackblazeUpload;
use Eduka\Cube\Actions\EpisodeStorage\UpdateBackblazeId;
use Eduka\Cube\Actions\FindCourseById;
use Eduka\Nova\Tasks\Traits\Notifier;
use Eduka\Services\External\Backblaze\BackblazeClient;
use Exception;

class HandleBackblazeUploadTask
{
    use Notifier;

    public function handle(int $storageId, int $courseId, array $notificationRecipients)
    {
        $notifier = new NotifyAdminTask;

        // @todo select only necessary columns
        $episodeStorage = FindEpisodeStorageForBackblazeUpload::find($storageId);

        if (! $episodeStorage) {
            $this->notifyEpisodeStorageNotFound($notifier, $notificationRecipients, $storageId);

            return;
        }

        $course = FindCourseById::find($courseId);

        if (! $course) {
            $this->notifyCourseNotFound($notifier, $notificationRecipients, $storageId);

            return;
        }

        $bbClient = new BackblazeClient;

        $existingBucket = $course->backblaze_bucket_name;

        // New bucket if a bucket with the name $bucketName does not exists
        $newBucketToBe = $course->canonical;

        try {
            // check if the bucket exists or not
            // if it doesn't,
            // create a new one
            $bucket = $bbClient->ensureBucketExists($existingBucket, $newBucketToBe);

            if ($existingBucket !== $bucket) {
                // a new bucket was created, update database
                UpdateBackblazeBucketName::update($course, $bucket);
            }

            $bbClient->uploadTo($episodeStorage->path_on_disk, $bucket, $episodeStorage->episode->name);

            UpdateBackblazeId::handle($episodeStorage, 'episode uploaded; attention: could not parse response');

            $this->notifyEpisodeUploadedSuccessfully($notifier, $notificationRecipients, $episodeStorage->episode->name, 'backblaze');
        } catch (Exception $e) {
            $this->notifyException($notifier, $notificationRecipients, $e);
        }
    }
}
