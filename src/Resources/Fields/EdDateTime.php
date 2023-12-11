<?php

namespace Eduka\Nova\Resources\Fields;

use Laravel\Nova\Fields\Text;

class EdDateTime extends Text
{
    public function __construct($name, $attribute = null, ?callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->readonly()
             ->hideFromIndex()
             ->hideWhenCreating()
             ->resolveUsing(function ($value) {
                 return $value ? \Carbon\Carbon::parse($value)->diffForHumans() : null;
             })
             ->canSee(function ($request) {
                 if (! via_resource()) {
                     return true;
                 }
             });
    }
}
