<?php

namespace Eduka\Nova;

use Aws\S3\S3Client;
use Eduka\Abstracts\Classes\EdukaServiceProvider;
use Eduka\Nova\Commands\DiskFileCleanup;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;

class EdukaNovaServiceProvider extends EdukaServiceProvider
{
    public function boot()
    {
        $this->registerCommands();

        $this->mergeConfigFrom(
            __DIR__.'/../config/eduka_nova.php',
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
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
            ]);

            $adapter = new AwsS3V3Adapter($client, $config['bucket']);

            return new Filesystem($adapter);
        });

        parent::boot();
    }

    public function register()
    {
        $this->app->register(NovaServiceProvider::class);
    }

    protected function registerCommands()
    {
        $this->commands([
            DiskFileCleanup::class,
        ]);
    }
}
