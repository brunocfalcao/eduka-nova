<?php

namespace Eduka\Nova\Resources\Actions;

use Eduka\Cube\Models\Video;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class MakeVisible extends Action
{
    protected array $data = ['is_visible' => true];

    public function handle(ActionFields $fields, Collection $models)
    {
        $ids = $models->pluck('id')->toArray();

        Video::whereIn('id', $ids)->update($this->data);

        return Action::message('Video status changed.');
    }

}
