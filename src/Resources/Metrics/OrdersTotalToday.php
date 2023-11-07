<?php

namespace Eduka\Nova\Resources\Metrics;

class OrdersTotalToday extends OrdersTotal
{
    public function ranges()
    {
        return [
            'TODAY' => 'Today',
        ];
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'orders-total-total';
    }
}
