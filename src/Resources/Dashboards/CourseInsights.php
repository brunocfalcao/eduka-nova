<?php

namespace Eduka\Nova\Resources\Dashboards;

use Hapheus\NovaSingleValueCard\NovaSingleValueCard;
use Laravel\Nova\Dashboard;

class CourseInsights extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new NovaSingleValueCard('Environment', config('app.env')),
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'main';
    }
}
