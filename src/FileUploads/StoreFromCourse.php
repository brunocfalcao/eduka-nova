<?php

namespace Eduka\Nova\FileUploads;

use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;

class StoreFromCourse
{
    /**
     * Store the incoming file upload.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $attribute
     * @param  string  $requestAttribute
     * @param  string|null  $disk
     * @param  string|null  $storagePath
     * @return array
     */
    public function __invoke(NovaRequest $request, $model, $attribute, $requestAttribute, $disk = null, $storagePath = null)
    {
        $randomFileName = Str::random(40).'.'.$request->$requestAttribute->getClientOriginalExtension();
        $path = $request->$requestAttribute->storeAs(
            $model->canonical,
            $randomFileName,
            $disk ?? 'public'
        );

        return [
            $requestAttribute => $path,
        ];
    }
}
