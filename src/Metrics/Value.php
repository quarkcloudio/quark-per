<?php

namespace QuarkCMS\QuarkAdmin\Metrics;

use QuarkCMS\Quark\Facades\Statistic;

/**
 * Class Value.
 */
abstract class Value extends Metric
{
    /**
     * The value's precision when rounding.
     *
     * @var int
     */
    public $precision = 0;

    /**
     * Return a value result showing the growth of an count aggregate over time.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  string|null  $dateColumn
     * @return \Laravel\Nova\Metrics\ValueResult
     */
    public function count($request, $model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($request, $model, 'count', $column, $dateColumn);
    }

    /**
     * Return a value result showing the growth of a model over a given time frame.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $function
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  string|null  $dateColumn
     * @return \QuarkCMS\QuarkAdmin\Metrics\ValueResult
     */
    protected function aggregate($request, $model, $function, $column = null, $dateColumn = null)
    {
        $query = gettype($model) === 'object' ? $model : (new $model)->newQuery();

        $column = $column ?? $query->getModel()->getQualifiedKeyName();

        return $this->result(
            round(with(clone $query)->{$function}($column), $this->precision)
        );
    }

    /**
     * Create a new value metric result.
     *
     * @param  mixed  $value
     * @return Statistic
     */
    public function result($value)
    {
        return Statistic::title($this->title)->value($value);
    }
}
