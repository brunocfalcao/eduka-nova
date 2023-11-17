<?php

namespace Eduka\Nova\Actions;

use Vimeo\Vimeo;

class UploadToVimeo
{
    public function upload(string $storagePath, array $metadata = [])
    {
        $client = new Vimeo(env('VIMEO_CLIENT_ID'), env('VIMEO_CLIENT_SECRET'), env('VIMEO_PERSONAL_ACCESS_TOKEN'));

        return $client->upload($storagePath, $metadata);
    }
}
