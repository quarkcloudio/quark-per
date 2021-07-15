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
    public static function buildIndexQuery(Request $request, $query, $search = [] , $filters = [])
    {
        return static::applyFilters($request, static::applySearch(
                $request,
                static::indexQuery($request, static::initializeQuery($request, $query)),
                $search
            ),
            $filters
        );
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
}
