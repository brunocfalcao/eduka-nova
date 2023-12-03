<?php

namespace Eduka\Nova\Resources\Fields;

use Laravel\Nova\Fields\MorphToMany;

class EdMorphToMany extends MorphToMany
{
    public function __construct($name, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute, $resource);

        $this->collapsedByDefault();
    }
}
