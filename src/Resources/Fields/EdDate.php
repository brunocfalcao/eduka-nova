<?php

namespace Eduka\Nova\Resources\Fields;

use Laravel\Nova\Fields\Date;

class EdDate extends Date
{
    public function __construct($name, $attribute = null, ?callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->onlyOnForms();
    }
}
