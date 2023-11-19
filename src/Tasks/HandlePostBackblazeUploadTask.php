<?php

namespace Eduka\Nova\Tasks;

use Eduka\Cube\Models\VideoStorage;

class HandlePostBackblazeUploadTask
{
    public function handle(VideoStorage $videoStorage, string $backblazeId)
    {
        $videoStorage->update([
            'backblaze_id' => $backblazeId,
        ]);
    }
}
