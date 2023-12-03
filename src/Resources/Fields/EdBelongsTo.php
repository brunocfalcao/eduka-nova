<?php

namespace Eduka\Nova\Resources\Fields;

use Laravel\Nova\Fields\BelongsTo;

class EdBelongsTo extends BelongsTo
{
    public function __construct($name, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute, $resource);

        $this->readonlyIfViaResource();
        $this->withoutTrashed();
    }
}
