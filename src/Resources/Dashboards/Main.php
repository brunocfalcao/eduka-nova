<?php

namespace Eduka\Nova\Resources\Dashboards;

use Eduka\Cube\Models\User;
use Eduka\Nova\Resources\Metrics\OrdersCount;
use Eduka\Nova\Resources\Metrics\OrdersTotal;
use Eduka\Nova\Resources\Metrics\OrdersTotalToday;
use Eduka\Nova\Resources\Metrics\UsersCount;
use Hapheus\NovaSingleValueCard\NovaSingleValueCard;
use Illuminate\Support\Facades\Auth;
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
        info('---');
        info(Auth::user()->courses->pluck('id'));
        info(User::fromCourses(Auth::user()->courses)->get()->count());
        info('---');
        return [
            new NovaSingleValueCard(
                'Users Total',
                /**
                 * Count all the users that have access to a specific course.
                 */
                User::fromCourses(Auth::user()->courses)->count()
            )
        ];
    }

    public function name()
    {
        return 'Dashboard';
    }
}
