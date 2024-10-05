<?php

namespace QuarkCloudIO\QuarkAdmin\Metrics;

use QuarkCloudIO\Quark\Facades\StatisticCard;
use QuarkCloudIO\Quark\Facades\Chart;

/**
 * Class Trend.
 */
abstract class Trend extends Metric
{
    /**
     * 统计单位
     */
    const BY_MONTHS = 'month';
    const BY_WEEKS = 'week';
    const BY_DAYS = 'day';
    const BY_HOURS = 'hour';
    const BY_MINUTES = 'minute';

    /**
     * 保留小数点位数
     *
     * @var int
     */
    public $precision = 0;

    /**
     * 按月计算数量
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null  $column
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function countByMonths($request, $model, $column = null)
    {
        return $this->count($request, $model, self::BY_MONTHS, $column);
    }

    /**
     * 按周计算数量
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null  $column
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function countByWeeks($request, $model, $column = null)
    {
        return $this->count($request, $model, self::BY_WEEKS, $column);
    }

    /**
     * 按日计算数量
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null  $column
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function countByDays($request, $model, $column = null)
    {
        return $this->count($request, $model, self::BY_DAYS, $column);
    }

    /**
     * 按小时计算数量
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null  $column
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function countByHours($request, $model, $column = null)
    {
        return $this->count($request, $model, self::BY_HOURS, $column);
    }

    /**
     * 按分计算数量
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null  $column
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function countByMinutes($request, $model, $column = null)
    {
        return $this->count($request, $model, self::BY_MINUTES, $column);
    }

    /**
     * 计算记录数量
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $unit
     * @param  string|null  $column
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function count($request, $model, $unit, $column = null)
    {
        $resource = $model instanceof Builder ? $model->getModel() : new $model;

        $column = $column ?? $resource->getQualifiedCreatedAtColumn();

        return $this->aggregate($request, $model, $unit, 'count', $resource->getQualifiedKeyName(), $column);
    }

    /**
     * 按月计算平均值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function averageByMonths($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_MONTHS, 'avg', $column, $dateColumn);
    }

    /**
     * 按周计算平均值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function averageByWeeks($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_WEEKS, 'avg', $column, $dateColumn);
    }

    /**
     * 按日计算平均值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function averageByDays($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_DAYS, 'avg', $column, $dateColumn);
    }

    /**
     * 按小时计算平均值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function averageByHours($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_HOURS, 'avg', $column, $dateColumn);
    }

    /**
     * 按分钟计算平均值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function averageByMinutes($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_MINUTES, 'avg', $column, $dateColumn);
    }

    /**
     * 计算平均值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $unit
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function average($request, $model, $unit, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, $unit, 'avg', $column, $dateColumn);
    }

    /**
     * 按月求和
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function sumByMonths($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_MONTHS, 'sum', $column, $dateColumn);
    }

    /**
     * 按周求和
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function sumByWeeks($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_WEEKS, 'sum', $column, $dateColumn);
    }

    /**
     * 按日求和
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function sumByDays($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_DAYS, 'sum', $column, $dateColumn);
    }

    /**
     * 按小时求和
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function sumByHours($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_HOURS, 'sum', $column, $dateColumn);
    }

    /**
     * 按分钟求和
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function sumByMinutes($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_MINUTES, 'sum', $column, $dateColumn);
    }

    /**
     * 计算求和值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $unit
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function sum($request, $model, $unit, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, $unit, 'sum', $column, $dateColumn);
    }

    /**
     * 按月取最大值
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Database\Eloquent\Builder|string $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return TrendResult
     */
    public function maxByMonths($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_MONTHS, 'max', $column, $dateColumn);
    }

    /**
     * 按周取最大值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function maxByWeeks($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_WEEKS, 'max', $column, $dateColumn);
    }

    /**
     * 按日取最大值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function maxByDays($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_DAYS, 'max', $column, $dateColumn);
    }

    /**
     * 按小时去最大值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function maxByHours($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_HOURS, 'max', $column, $dateColumn);
    }

    /**
     * 按分钟取最大值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function maxByMinutes($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_MINUTES, 'max', $column, $dateColumn);
    }

    /**
     * 取最大值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $unit
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function max($request, $model, $unit, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, $unit, 'max', $column, $dateColumn);
    }

    /**
     * 按月取最小值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function minByMonths($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_MONTHS, 'min', $column, $dateColumn);
    }

    /**
     * 按周取最小值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function minByWeeks($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_WEEKS, 'min', $column, $dateColumn);
    }

    /**
     * 按日取最小值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function minByDays($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_DAYS, 'min', $column, $dateColumn);
    }

    /**
     * 按小时取最小值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function minByHours($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_HOURS, 'min', $column, $dateColumn);
    }

    /**
     * 按分钟取最小值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function minByMinutes($request, $model, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, self::BY_MINUTES, 'min', $column, $dateColumn);
    }

    /**
     * 取最小值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $unit
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    public function min($request, $model, $unit, $column, $dateColumn = null)
    {
        return $this->aggregate($request, $model, $unit, 'min', $column, $dateColumn);
    }

    /**
     * 设置小数点位数
     *
     * @param  int  $precision
     * @return $this
     */
    public function precision($precision = 0)
    {
        $this->precision = $precision;

        return $this;
    }

    /**
     * 计算值
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $unit
     * @param  string  $function
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    protected function aggregate($request, $model, $unit, $function, $column, $dateColumn = null)
    {
        $query = gettype($model) === 'object' ? $model : (new $model)->newQuery();

        $wrappedColumn = $column instanceof Expression
                ? (string) $column
                : $query->getQuery()->getGrammar()->wrap($column);

        $results = $query
                ->select(DB::raw("{$expression} as date_result, {$function}({$wrappedColumn}) as aggregate"))
                ->whereBetween(
                    $dateColumn, array_map(function ($date) {
                        return $this->asQueryDatetime($date);
                    }, [$startingDate, $endingDate])
                )->groupBy(DB::raw($expression))
                ->orderBy('date_result')
                ->get();

        $results = array_merge($possibleDateResults, $results->mapWithKeys(function ($result) use ($request, $unit) {
            return [$this->formatAggregateResultDate(
                $result->date_result, $unit, $request->twelveHourTime === 'true'
            ) => round($result->aggregate, $this->precision)];
        })->all());

        if (count($results) > $request->range) {
            array_shift($results);
        }

        return $this->result($results);
    }

    /**
     * 返回结果
     *
     * @param  mixed  $value
     * @return StatisticCard
     */
    public function result($value)
    {
        return StatisticCard::title($this->title)->chart($value);
    }
}
