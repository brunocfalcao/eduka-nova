<?php

namespace Eduka\Nova\Resources\Dashboards;

use Eduka\Nova\Resources\Metrics\OrdersCount;
use Eduka\Nova\Resources\Metrics\OrdersTotal;
use Eduka\Nova\Resources\Metrics\OrdersTotalToday;
use Eduka\Nova\Resources\Metrics\UsersCount;
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
        return [
            new UsersCount,
            new OrdersTotal,
            new OrdersCount,
            new OrdersTotalToday,
        ];
    }

    public function name()
    {
        return 'Dashboard';
    }
}
