<?php

namespace Eduka\Nova\Resources\Dashboards;

use Hapheus\NovaSingleValueCard\NovaSingleValueCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        $courseIds = Auth::user()->courses->pluck('id');

        return [
            new NovaSingleValueCard(
                'Users Total',
                DB::table('users')
                    ->select('users.*')
                    ->distinct()
                    ->join('user_variant', 'users.id', 'user_variant.user_id')
                    ->join('variants', 'user_variant.variant_id', 'variants.id')
                    ->join('courses', 'variants.course_id', 'courses.id')
                    ->whereIn('courses.id', $courseIds)->count()
            ),
        ];
    }

    public function name()
    {
        return 'Dashboard';
    }
}
