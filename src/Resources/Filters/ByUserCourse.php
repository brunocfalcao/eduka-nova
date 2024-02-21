<?php

namespace Eduka\Nova\Resources\Filters;

use Eduka\Cube\Models\Course;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ByUserCourse extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    public $name = 'By Course';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        log_queries();

        return $query->bring('course_user')
            ->where('course_user.course_id', $value);

        stop_logging_queries();
    }

    /**
     * Get the filter's available options.
     *
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return Course::pluck('id', 'name')->all();
    }
}
