<?php

namespace Eduka\Nova;

use Eduka\Abstracts\Classes\EdukaServiceProvider;

class EdukaNovaServiceProvider extends EdukaServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    public function register()
    {
        $this->app->register(NovaServiceProvider::class);
    }
}
