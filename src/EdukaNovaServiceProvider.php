<?php

namespace Eduka\Nova;

use Aws\S3\S3Client;
use Eduka\Abstracts\Classes\EdukaServiceProvider;
use Exception;
use Illuminate\Support\Facades\Storage;
use BackblazeB2\Client;
use Eduka\Nova\Backblaze\BackblazeAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;

class EdukaNovaServiceProvider extends EdukaServiceProvider
{
    public function boot()
    {
        parent::boot();

       

        // Storage::extend('b2', function ($app, $config) {
        //     // check if all required configuration keys are given.
        //     if (
        //         !isset($config['accountId']) ||
        //         !isset($config['applicationKey']) ||
        //         !isset($config['bucketName'])
        //     ) {
        //         throw new Exception('Please set the "accountId", "applicationKey" and "bucketName" configuration keys.');
        //     }

        //     // create a client
        //     $client = new Client($config['accountId'], $config['applicationKey']);

        //     // create an new adapter
        //     $adapter = new BackblazeAdapter($client, $config['bucketName']);

        //     // and return the file system.
        //     return new FilesystemAdapter(
        //         new Filesystem($adapter, $config),
        //         $adapter,
        //         $config
        //     );
        // });
    }

    public function register()
    {
        $this->app->register(NovaServiceProvider::class);
    }
}
