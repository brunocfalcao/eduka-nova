<?php

namespace Eduka\Nova\Resources\Fields;

use Laravel\Nova\Fields\Textarea;

class EdTextarea extends Textarea
{
    public function __construct__construct($name, $attribute = null, ?callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->hideFromIndex();
    }
}
