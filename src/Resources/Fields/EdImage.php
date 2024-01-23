<?php

namespace Eduka\Nova\Resources\Fields;

use Laravel\Nova\Fields\Image;

class EdImage extends Image
{
    public function __construct($name, $attribute = null, $disk = null, $storageCallback = null)
    {
        parent::__construct($name, $attribute, $disk, $storageCallback);

        $this->disableDownload()
            ->detailWidth(350)
            ->acceptedTypes('image/*');
    }
}
