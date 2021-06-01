<?php

namespace QuarkCMS\QuarkAdmin\Filters;

use Illuminate\Http\Request;

/**
 * Class Filter.
 */
abstract class Filter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = null;

    /**
     * Get the displayable name of the filter.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * The default value of the filter.
     *
     * @var string
     */
    public function default()
    {
        return true;
    }

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
        return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [];
    }
}
