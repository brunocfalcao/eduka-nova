<?php

namespace Eduka\Nova\Resources\Fields;

use Laravel\Nova\Fields\Text;

class EdUUID extends Text
{
    public function __construct($name, $attribute = null, ?callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->exceptOnForms();
        $this->readonly();
        $this->hideFromIndex();
    }
}
