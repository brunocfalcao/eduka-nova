<?php

namespace Eduka\Nova\Metrics\Subscriber;

use Eduka\Cube\Models\Subscriber;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class NewSubscribers extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Subscriber::class);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        // Create some computed data ranges.
        $thisWeek = this_week_days();

        return [
            'TODAY' => __('Today'),
            $thisWeek => __('This week'),
            30 => __('30 Days'),
            60 => __('60 Days'),
            'MTD' => __('This Month'),
            'YTD' => __('This Year'),
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'new-subscribers';
    }
}
