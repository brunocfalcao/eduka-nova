<?php

namespace Eduka\Nova\Tasks;

use Eduka\Cube\Actions\Variant\UpdateVimeoProjectId;
use Eduka\Cube\Actions\Video\SaveVimeoId;
use Exception;
use Eduka\Cube\Shared\Processor\VimeoUploaderValidator;
use Eduka\Nova\Tasks\Traits\Notifier;
use Eduka\Services\External\Vimeo\VimeoClient;

class HandleVimeoUploadTask
{
    use Notifier;

    public function handle(int $videoId, array $notificationRecipients = [])
    {
        $notifier = new NotifyAdminTask;

        $validator = VimeoUploaderValidator::findUsingVideoId($videoId);

        $vimeoClient = new VimeoClient;

        try {

            $validator
                ->ensureDataExistsInDatabase()
                ->ensureVideoExistsOnDisk();

            $newVimeoProjectId = $vimeoClient->ensureProjectExists($validator->getVimeoProjectId(), $validator->getCourseName());

            if ($newVimeoProjectId !== $validator->getVimeoProjectId()) {
                UpdateVimeoProjectId::update($validator->getVariant(), $newVimeoProjectId);

                $validator = $validator->refreshVariant();
            }

            $vimeoUrl = $vimeoClient->upload($validator->getVideoFilePathFromDisk(), $validator->getVideoMetadata());

            $vimeoId = $vimeoClient->getIdFromResponse($vimeoUrl);

            SaveVimeoId::save(
                $validator->getVideo(),
                $validator->getVideoStorage(),
                $vimeoId
            );

            $vimeoClient->moveVideoToFolder($validator->getVimeoProjectId(), $vimeoId);
        } catch (Exception $e) {

            $this->notifyException($notifier, $notificationRecipients, $e);

            throw $e;
        }

        $this->notifyVideoUploadedSuccessfully($notifier, $notificationRecipients, $validator->getVideoName(), $validator->getNotificationChannelName());
    }
}
