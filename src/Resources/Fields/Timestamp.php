<?php

namespace Eduka\Nova\Resources\Fields;

use Laravel\Nova\Fields\Text;

class Timestamp extends Text
{
    public function __construct($name, $attribute = null, ?callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this
            ->onlyOnDetail()
            ->resolveUsing(function ($value) {
                return $value ? \Carbon\Carbon::parse($value)->diffForHumans() : null;
            });
    }
}
