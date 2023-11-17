<?php

namespace Eduka\Nova\Actions;

use Eduka\Cube\Models\Video;
use Eduka\Cube\Models\VideoStorage;

class HandlePostVimeoUploadTask
{
    public function handle(Video $video, int $vimeoId, string $videoPathOnDisk)
    {
        $video->update(['vimeo_id' => $vimeoId]);

        $videoStorage = VideoStorage::where('video_id', $video->id)
            ->first();

        if ($videoStorage) {
            $videoStorage->update([
                'vimeo_id' => $vimeoId,
            ]);
        } else {
            $videoStorage = VideoStorage::create([
                'vimeo_id' => $vimeoId,
                'video_id' => $video->id,
                'path_on_disk' => $videoPathOnDisk,
            ]);
        }
    }
}
