<?php

namespace Eduka\Nova\Services;

use Illuminate\Http\Request;

class MetaImageLogoAttachment
{
    /**
     * Store the incoming file upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $attribute
     * @param  string  $requestAttribute
     * @param  string  $disk
     * @param  string  $storagePath
     * @return array
     */
    public function __invoke(Request $request, $model, $attribute, $requestAttribute, $disk, $storagePath)
    {
        $filename = $request->{$attribute}->getClientOriginalName();

        return [
            'meta_image' => $request->{$attribute}->storePubliclyAs('/'.$model->canonical.'/meta', $filename, 'public'),
        ];
    }
}
