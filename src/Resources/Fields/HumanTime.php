<?php

namespace Eduka\Nova\Resources\Fields;

use Laravel\Nova\Fields\Text;

class HumanTime extends Text
{
    public function __construct($name, $attribute = null, ?callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this
            ->exceptOnForms()
            ->resolveUsing(function ($value) {
                return $value ? \Carbon\Carbon::parse($value)->diffForHumans() : null;
            });
    }
}
