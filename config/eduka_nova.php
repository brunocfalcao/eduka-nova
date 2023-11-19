<?php

return [
    'backblaze' => [
        'region' => env('us-east-005'),
        'url' => env('BACKBLAZE_ENDPOINT'),
        'key' => env('BACKBLAZE_KEY_ID'),
        'secret' => env('BACKBLAZE_APP_KEY'),
        'bucket' => env('BACKBLAZE_BUCKET_NAME'),
    ],
];
