<?php

namespace Eduka\Nova\Resources\Actions;

use Eduka\Nova\Jobs\HandleVimeoUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class UploadVideo extends Action
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() > 1) {
            return Action::danger('Please run this on only one video resource.');
        }

        $path = Storage::disk('local')->putFile('videos', $fields->video);

        HandleVimeoUpload::dispatch($models->first()->id, $path);

        return Action::message('Video upload started . ', $path);
    }

    public function fields(NovaRequest $request)
    {
        return [
            File::make('Video'),

            Select::make('Course'),
        ];
    }
}
