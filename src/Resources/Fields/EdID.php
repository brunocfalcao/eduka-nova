<?php

namespace Eduka\Nova\Resources\Fields;

use Laravel\Nova\Fields\ID;

class EdID extends ID
{
    public function __construct($name = 'ID', $attribute = 'id', $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->sortable();

        $this->hideFromIndex();
    }
}
