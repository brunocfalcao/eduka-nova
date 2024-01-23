<?php

namespace Eduka\Nova\Abstracts;

use App\Nova\Resource;
use Eduka\Nova\Resources\Fields\Timestamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Titasgailius\SearchRelations\SearchesRelations;

abstract class EdukaResource extends Resource
{
    use SearchesRelations;

    public static function softDeletes()
    {
        return false;
    }

    public function user()
    {
        return Auth::user();
    }

    protected function timestamps(Request $request)
    {
        return [
            Timestamp::make('Created At'),

            Timestamp::make('Updated At')
                ->canSee(fn () => ! $request->findModel()->updated_at == null),

            Timestamp::make('Deleted At')
                ->canSee(fn () => ! $request->findModel()->deleted_at == null),
        ];
    }
}
