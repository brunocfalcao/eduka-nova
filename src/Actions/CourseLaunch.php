<?php

namespace Eduka\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class CourseLaunch extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Launch';

    public $withoutActionEvents = true;

    public $onlyOnIndex = true;

    public $confirmText = 'Are you sure you want to launch this course?';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $course = $models->first();
        $course->launch();
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
