<?php

namespace Eduka\Nova\Resources\Fields;

use Laravel\Nova\Fields\BelongsToMany;

class EdBelongsToMany extends BelongsToMany
{
    public function __construct($name, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute, $resource);

        $this->collapsedByDefault();
    }
}
