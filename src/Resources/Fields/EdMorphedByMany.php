<?php

namespace Eduka\Nova\Resources\Fields;

use Laravel\Nova\Fields\MorphedByMany;

class EdMorphedByMany extends MorphedByMany
{
    public function __construct($name, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute, $resource);

        $this->collapsedByDefault();
    }
}
