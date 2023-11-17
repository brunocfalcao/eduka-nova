<?php

namespace Eduka\Nova;

use BackblazeB2\Client;
use Eduka\Abstracts\Classes\EdukaServiceProvider;
use Eduka\Nova\Backblaze\BackblazeAdapter;
use Exception;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;

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
