<?php

namespace Eduka\Nova;

use Aws\S3\S3Client;
use Eduka\Abstracts\Classes\EdukaServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;

class EdukaNovaServiceProvider extends EdukaServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->mergeConfigFrom(
            __DIR__ . '/../config/eduka_nova.php',
            'eduka_nova'
        );

        // Register the Backblaze disk
        Storage::extend('backblaze', function ($app, $config) {
            $config = $config['backblaze'];

            $client = new S3Client([
                'version' => 'latest',
                'region' => $config['region'],
                'endpoint' => $config['url'],
                'credentials' => [
                    'key'    => $config['key'],
                    'secret' => $config['secret'],
                ],
            ]);

            $adapter = new AwsS3V3Adapter($client, $config['bucket']);

            return new Filesystem($adapter);
        });
    }

    public function register()
    {
        $this->app->register(NovaServiceProvider::class);
    }
}
