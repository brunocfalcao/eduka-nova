<?php

namespace Eduka\Nova\Resources\Dashboards;

use Laravel\Nova\Dashboard;
use Hapheus\NovaSingleValueCard\NovaSingleValueCard;

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
        return 'course-insights';
    }
}
