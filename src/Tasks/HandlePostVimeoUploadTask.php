<?php

namespace Eduka\Nova\Tasks;

use Eduka\Cube\Models\Video;
use Eduka\Cube\Models\VideoStorage;
use Exception;

class HandlePostVimeoUploadTask
{
    public function handle(Video $video, int $vimeoId)
    {
        $video->update(['vimeo_id' => $vimeoId]);

        $videoStorage = VideoStorage::where('video_id', $video->id)
            ->first();

        if (! $videoStorage) {
            throw new Exception('video storage not found');
        }

        $videoStorage->update([
            'vimeo_id' => $vimeoId,
        ]);
    }
}
