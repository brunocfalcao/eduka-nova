<?php

namespace Eduka\Nova\Filters\User;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class PreSubscribed extends Filter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        switch ($value) {
            case 'pre-subscribed':
                return $query->whereNotNull('subscriber_id');
                break;

            case 'not-pre-subscribed':
                return $query->whereNull('subscriber_id');
                break;

            default:
                return $query;
        }
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Pre-Subscribed' => 'pre-subscribed',
            'Did not Pre-Subscribed' => 'not-pre-subscribed',
        ];
    }
}
