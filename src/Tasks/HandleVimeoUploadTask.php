<?php

namespace Eduka\Nova\Tasks;

<<<<<<< HEAD
use Eduka\Cube\Models\Video;
use Eduka\Nova\Services\Vimeo\VimeoClient;
use Eduka\Nova\Tasks\Traits\Notifier;
use Exception;
use Illuminate\Support\Facades\Storage;
=======
use Eduka\Cube\Actions\Variant\UpdateVimeoProjectId;
use Eduka\Cube\Actions\Video\SaveVimeoId;
use Exception;
use Eduka\Cube\Shared\Processor\VimeoUploaderValidator;
use Eduka\Nova\Tasks\Traits\Notifier;
use Eduka\Services\External\Vimeo\VimeoClient;
>>>>>>> c302ee3 (Refactoring)

class HandleVimeoUploadTask
{
    use Notifier;

    public function handle(int $videoId, array $notificationRecipients = [])
    {
        $notifier = new NotifyAdminTask;

        $validator = VimeoUploaderValidator::findUsingVideoId($videoId);

<<<<<<< HEAD
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
=======
        $vimeoClient = new VimeoClient;
>>>>>>> c302ee3 (Refactoring)

        try {

            $validator
                ->ensureDataExistsInDatabase()
                ->ensureVideoExistsOnDisk();

            if(! $validator->getVimeoProjectId()) {
                $newVimeoProjectId = $vimeoClient->ensureProjectExists(null, $validator->getCourseName());
                UpdateVimeoProjectId::update($validator->getVariant() , $newVimeoProjectId);
            }

            $vimeoUrl = $vimeoClient->upload($validator->getVideoFilePathFromDisk(), $validator->getVideoMetadata());

            $vimeoId = $vimeoClient->getIdFromResponse($vimeoUrl);

            SaveVimeoId::save(
                $validator->getVideo(),
                $validator->getVideoStorage(),
                $vimeoId
            );

        } catch (Exception $e) {

            $this->notifyException($notifier, $notificationRecipients, $e);

            throw $e;
        }

        $this->notifyVideoUploadedSuccessfully($notifier, $notificationRecipients, $validator->getVideoName(), $validator->getNotificationChannelName());
    }
}
