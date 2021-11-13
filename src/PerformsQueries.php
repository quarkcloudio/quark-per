<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;

trait PerformsQueries
{
    /**
     * 创建列表查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return Builder
     */
    public static function buildIndexQuery(Request $request, $query, $search = [] , $filters = [], $columnFilters = [], $orderings = [])
    {
        return static::applyOrderings(
            static::applyColumnFilters(
                static::applyFilters(
                    $request,
                    static::applySearch(
                        $request,
                        static::indexQuery(
                            $request,
                            static::initializeQuery($request, $query)
                        ),
                    $search),
                    $filters
                ),
            $columnFilters),
        $orderings);
    }

    /**
     * 创建详情页查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return Builder
     */
    public static function buildDetailQuery(Request $request, $query)
    {
        return static::detailQuery($request, static::initializeQuery($request, $query));
    }

    /**
     * 创建导出查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return Builder
     */
    public static function buildExportQuery(Request $request, $query, $search = [] , $filters = [], $columnFilters = [], $orderings = [])
    {
        return static::applyOrderings(
            static::applyColumnFilters(
                static::applyFilters(
                    $request,
                    static::applySearch(
                        $request,
                        static::exportQuery(
                            $request,
                            static::initializeQuery($request, $query)
                        ),
                    $search),
                    $filters
                ),
            $columnFilters),
        $orderings);
    }

    /**
     * 初始化查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return Builder
     */
    protected static function initializeQuery(Request $request, $query)
    {
        return static::query($request, $query);
    }

    /**
     * 执行搜索表单查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @param  array  $search
     * @return Builder
     */
    protected static function applySearch(Request $request, $query, $search)
    {
        foreach ($search as $key => $value) {
            $query = $value->__invoke($request, $query);
        }
        
        return $query;
    }

    /**
     * 执行表格列上过滤器查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @param  array  $filters
     * @return Builder
     */
    protected static function applyColumnFilters($query, $filters)
    {
        $filters = array_filter($filters);

        if (empty($filters)) {
            return $query;
        }

        foreach ($filters as $column => $direction) {
            $query = $query->whereIn($column, $direction);
        }

        return $query;
    }

    /**
     * 执行过滤器查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @param  array  $filters
     * @return Builder
     */
    protected static function applyFilters(Request $request, $query, $filters)
    {
        return $query;
    }

    /**
     * 执行排序查询
     *
     * @param  Builder  $query
     * @param  array  $orderings
     * @return Builder
     */
    protected static function applyOrderings($query, $orderings)
    {
        $orderings = array_filter($orderings);

        if (empty($orderings)) {
            return empty($query->getQuery()->orders) ? $query->latest($query->getModel()->getQualifiedKeyName())
                        : $query;
        }

        foreach ($orderings as $column => $direction) {
            $direction = ($direction === 'descend') ? 'desc' : 'asc';
            $query = $query->orderBy($column, $direction);
        }

        return $query;
    }

    /**
     * 全局查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return Builder
     */
    public static function query(Request $request, $query)
    {
        return $query;
    }

    /**
     * 列表查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return Builder
     */
    public static function indexQuery(Request $request, $query)
    {
        return $query;
    }

    /**
     * 详情查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return Builder
     */
    public static function detailQuery(Request $request, $query)
    {
        return $query;
    }

    /**
     * 导出查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return Builder
     */
    public static function exportQuery(Request $request, $query)
    {
        return $query;
    }
}
